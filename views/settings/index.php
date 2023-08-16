<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
if (isset($_POST["refresh"]) && $_POST["refresh"] == "true") {
    extract($session->sql->single("SELECT * FROM `discord_secrets`"));
    $host = $_SERVER["HTTP_HOST"];
    $post_fields = http_build_query([
        "client_id"     => $client_id,
        "client_secret" => $client_secret,
        "grant_type"    => "refresh_token",
        "refresh_token" => $session->user["discord_refresh_token"],
    ]);
    // Phase 1
    $url1 = "https://discordapp.com/api/oauth2/token";
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $url1);
    curl_setopt($ch1, CURLOPT_POST, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    $result1 = curl_exec($ch1);
    if (!$result1) {
        $session->error(500);
    }
    $json1 = json_decode($result1, true) or $session->error(500);
    extract($json1);
    if (
        !isset($access_token) ||
        !isset($refresh_token) ||
        !isset($expires_in)
    ) {
        $session->error(500);
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
        $session->error(500);
    }
    $json2 = json_decode($result2, true) or $session->error(500);
    extract($json2) or $session->error(500);
    if (
        !isset($id) ||
        !isset($username) ||
        !isset($avatar) ||
        !isset($discriminator) ||
        !isset($email) ||
        !isset($global_name) ||
        !isset($verified)
    ) {
        $session->error(500);
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

    $username = $session->sql->escape($username);
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
			`login_last` = CURRENT_TIMESTAMP()");

    $query = "UPDATE `ip` SET `logins` = `logins` + 1 WHERE `id` = '{$session->ip_id}';\n";
    $query .= "UPDATE `sessions_app` SET `logins` = `logins` + 1, `user_id` = '$id' WHERE `session_id` = '{$session->id}';\n";
    $session->sql->multi($query);
    $session->refresh();
}
$session->header("Settings");
?>
<style>
    th {
        width: 25%;
        text-align: right;
    }

    td {
        width: 75%;
        text-align: left;
    }
</style>
<div class="discroller">
    <div class="card" style="width: calc(100% - 10px); min-height: calc(100% - 10px); height: fit-content;">
        <div class="flex-container">
            <div class="card">
                <div class="card-header">
                    <h3>Discord Profile</h3>
                </div>
                <div class="card-body">
                    <?php
                    extract($session->user);
                    $email = explode("@", $discord_email);
                    $email[0] = substr($email[0], 0, 1) . str_repeat("*", strlen($email[0]) - 2) . substr($email[0], -1);
                    $email[1] = substr($email[1], 0, 1) . str_repeat("*", strlen($email[1]) - 2) . substr($email[1], -1);
                    $discord_email = implode("@", $email);

                    if ($discord_verified) $discord_verified = "Yes";
                    else $discord_verified = "No";

                    if ($api_token == "") $api_token = "Not Set";
                    else $api_token = substr($api_token, 0, 4) . str_repeat("*", strlen($api_token) - 8) . substr($api_token, -4);
                    ?>
                    <table class="table table-sm">
                        <tr>
                            <th>ID</th>
                            <td><?= $discord_id ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?= $discord_username ?></td>
                        </tr>
                        <tr>
                            <th>Display As</th>
                            <td><?= $discord_global_name ?></td>
                        </tr>
                        <tr>
                            <th>Avatar</th>
                            <td><img src="<?= $discord_avatar ?>" width="64" height="64"></td>
                        </tr>
                        <tr>
                            <th>Discriminator</th>
                            <td><?= $discord_discriminator ?></td>
                        </tr>
                        <tr>
                            <th>E-mail</th>
                            <td><?= $discord_email ?></td>
                        </tr>
                        <tr>
                            <th>Verified</th>
                            <td><?= $discord_verified ?></td>
                        </tr>
                    </table>
                </div>
                <form method="post">
                    <input type="hidden" name="refresh" value="true">
                    <div class="card-footer">
                        <center>
                            <div style="max-width: 270px;">To change any of the above, update your Discord account first, then click Resfresh.</div>
                            <button class="btn btn-primary"><i style="vertical-align: middle;" class="mdi mdi-refresh"></i> Refresh</button>
                            <div>Last Refreshed</div>
                            <div><?= $session->user["login_last"] ?> UTC</div>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php
$session->footer();
