<?php

namespace Rpurinton\qi;

require_once(__DIR__ . "/../src/Session.php");
$session = new Session(true);
if ($session->loggedin) $session->redirect("/");
$host = $_SERVER["HTTP_HOST"];
$url = "https://discord.com/api/oauth2/authorize?client_id=1095501256778317966&redirect_uri=https%3A%2F%2Fqi.discommand.com%2Flogin%2Fprocess%2F&response_type=code&scope=identify%20guilds%20guilds.members.read%20email";
header("Location: $url", true, 302);
