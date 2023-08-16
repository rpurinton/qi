<?php
require_once(__DIR__ . "/include/functions.php");
$tests[] = function ($host) {
    $uri = "index.php";

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

    $name = "200ok";
    if ($http_response_header[0] === "HTTP/1.1 200 OK") {
        pass();
        $name = "contains-Laozi";
        if (strpos($result, "Laozi") !== false) {
            pass();
        } else {
            fail($uri, $name, 0);
        }
    } else {
        fail($uri, $name, 1);
    }
};
