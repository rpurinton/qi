<?php
require_once(__DIR__ . "/include/functions.php");
$tests[] = function ($host) {
    $uri = "api/";

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
        $name = "isJson";
        if (isJson($result)) {
            pass();
            $name = "isset-error";
            $result2 = json_decode($result, true);
            if (isset($result2["error"])) {
                pass();
                $name = "error=no endpoint";
                if ($result2["error"] === "no endpoint") {
                    pass();
                } else {
                    fail($uri, $name, 0);
                }
            } else {
                fail($uri, $name, 1);
            }
        } else {
            fail($uri, $name, 2);
        }
    } else {
        fail($uri, $name, 3);
    }
};
