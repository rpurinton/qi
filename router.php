<?php
$path = __DIR__ . "/views" . explode("?", $_SERVER['REQUEST_URI'])[0];
while (!file_exists($path . "/index.php")) $path = substr($path, 0, strrpos($path, "/"));
require_once($path . "/index.php");
