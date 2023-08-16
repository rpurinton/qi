<?php
require_once(__DIR__ . "/include/functions.php");
$tests[] = function ($host) {
    $uri = "login/process/";

    $start_time = microtime(true);
    $result = @file_get_contents($host . $uri);
    $end_time = microtime(true);

    $lag = $end_time - $start_time;
    $name = "lagtime = $lag";
    if ($lag < 1) {
        pass();
    } else {
        fail($uri, $name, 0);
    }

    $name = "403forbidden";
    if ($http_response_header[0] === "HTTP/1.1 403 Forbidden") {
        pass();
    } else {
        fail($uri, $name, 0);
    }
};
