<?php
require_once(__DIR__ . "/include/functions.php");
$tests[] = function ($host) {
    $uri = "login/";

    $start_time = microtime(true);
    $result = file_get_contents($host . $uri);
    $end_time = microtime(true);

    $lag = $end_time - $start_time;
    $name = "lagtime = $lag";
    if ($lag < 1) {
        pass();
    } else {
        fail($uri, $name, 0);
    }

    $name = "302found";
    if ($http_response_header[0] === "HTTP/1.1 302 Found") {
        pass();
        $name = "isHTML";
        if (substr($result, 0, 15) == "<!DOCTYPE html>") {
            pass();
        } else {
            fail($uri, $name, 0);
        }
    } else {
        fail($uri, $name, 1);
    }
};
