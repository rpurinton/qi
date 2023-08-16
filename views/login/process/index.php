<?php

namespace Rpurinton\qi;

use Monolog\Handler\Handler;

try {
	require_once(__DIR__ . "/../../src/Session.php");
	$session = new Session(true);

	if (isset($_GET["error"]) && $_GET["error"] == "access_denied") $session->redirect("/logout");
	if (!isset($_GET["code"])) $session->error(403);

	$code = $_GET["code"];
	$host = $_SERVER["HTTP_HOST"];
	extract($session->sql->single("SELECT * FROM `discord_secrets`"));
	$post_fields = http_build_query([
		"code"          => $code,
		"client_id"     => $client_id,
		"client_secret" => $client_secret,
		"grant_type"    => "authorization_code",
		"redirect_uri"  => "https://$host/login/process/",
		"scope"         => "identify%20email"
	]);
	// same but for refresh
	// $post_fields = http_build_query([
	// 	"client_id"     => $client_id,
	// 	"client_secret" => $client_secret,
	// 	"grant_type"    => "refresh_token",
	// 	"refresh_token" => $refresh_token,
	// 	"redirect_uri"  => "https://$host/login/process/",
	// 	"scope"         => "identify%20email"
	// ]);
	// Phase 1
	$url1 = "https://discordapp.com/api/oauth2/token";
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, $url1);
	curl_setopt($ch1, CURLOPT_POST, true);
	curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	$result1 = curl_exec($ch1);
	if (!$result1) {
		handle_error("Curl failed: " . curl_error($ch1) . " result1: $result1");
	}
	$json1 = json_decode($result1, true) or handle_error("json_decode failed: " . print_r($result1, true));
	extract($json1) or handle_error("extract failed: " . print_r($json1, true));
	if (
		!isset($access_token) ||
		!isset($refresh_token) ||
		!isset($expires_in)
	) {
		handle_error("Missing access_token, refresh_token, or expires_in from " . print_r($json1, true));
	}

	$expires_at = time() + $expires_in;

	// Phase 2
	$url2 = "https://discordapp.com/api/users/@me";
	$header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL, $url2);
	curl_setopt($ch2, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	$result2 = curl_exec($ch2);
	if (!$result2) {
		handle_error("Curl2 failed: " . curl_error($ch2) . " result2: $result2");
	}
	$json2 = json_decode($result2, true) or handle_error("json_decode failed: " . print_r($result2, true));
	extract($json2) or handle_error("extract failed: " . print_r($json2, true));
	if (
		!isset($id) ||
		!isset($username) ||
		!isset($discriminator) ||
		!isset($email) ||
		!isset($verified)
	) {
		handle_error("Missing id, username, avatar, discriminator, email, or verified from " . print_r($json2, true));
	}
	/*
Array ( [id] => 363853952749404162 
[username] => laozi101 
[avatar] => befb0411a51c364505e1403720980969 
[discriminator] => 0 
[public_flags] => 64
[flags] => 64 
[banner] =>
[accent_color] => 0 
[global_name] => Laozi 
[avatar_decoration] => 
[banner_color] => #000000 
[mfa_enabled] => 
[locale] => en-US 
[premium_type] => 0 
[email] => russell.purinton@gmail.com 
[verified] => 1 
)
*/
	if (!isset($global_name)) $global_name = $username;
	if (!isset($avatar)) $avatar = "";
	$username = $session->sql->escape($username);
	$global_name = $session->sql->escape($global_name);
	$email = $session->sql->escape($email);
	$trial_expires = time() + (7 * 24 * 60 * 60); // +7 Days
	$session->sql->query("INSERT INTO `discord_users` (
			`discord_id`,
			`discord_username`,
			`discord_global_name`,
			`discord_avatar`,
			`discord_discriminator`,
			`discord_email`,
			`discord_verified`,
			`discord_access_token`,
			`discord_refresh_token`,
			`discord_expires_at`,
			`trial_expires`
			) VALUES (
			'$id',
			'$username',
			'$global_name',
			'$avatar',
			'$discriminator',
			'$email',
			'$verified',
			'$access_token',
			'$refresh_token',
			'$expires_at',
			'$trial_expires' )
			ON DUPLICATE KEY UPDATE
			`discord_username` = '$username',
			`discord_global_name` = '$global_name',
			`discord_avatar` = '$avatar',
			`discord_discriminator` = '$discriminator',
			`discord_email` = '$email',
			`discord_verified` = '$verified',
			`discord_access_token` = '$access_token',
			`discord_refresh_token` = '$refresh_token',
			`discord_expires_at` = '$expires_at',
			`logins` = `logins` + 1,
			`views` = `views` + 1,
			`login_last` = CURRENT_TIMESTAMP()");

	$query = "UPDATE `ip` SET `logins` = `logins` + 1 WHERE `id` = '{$session->ip_id}';\n";
	$query .= "UPDATE `sessions_app` SET `logins` = `logins` + 1, `user_id` = '$id' WHERE `session_id` = '{$session->id}';\n";
	$session->sql->multi($query);
	$session->redirect("/favorites");
} catch (\Exception $e) {
	print_r($e);
} catch (\Error $e) {
	print_r($e);
} catch (\Throwable $e) {
	print_r($e);
}

function handle_error($message)
{
	global $session;
	$session->error(500);
}
