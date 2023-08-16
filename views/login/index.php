<?php

namespace Rpurinton\qi;

require_once(__DIR__ . "/../src/Session.php");
$session = new Session(true);
if ($session->loggedin) $session->redirect("/");
$host = $_SERVER["HTTP_HOST"];
$url = "https://discord.com/api/oauth2/authorize?client_id=1064074534761799761&redirect_uri=https%3A%2F%2F$host%2Flogin%2Fprocess%2F&response_type=code&scope=email%20identify";
header("Location: $url", true, 302);
