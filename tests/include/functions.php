<?php
function pass()
{
    global $pass;
    $pass++;
}

function fail($uri, $name, $skipped)
{
    global $fail, $skip;
    $fail++;
    $skip += $skipped;
    echo("FAILED $uri/$name\n");
}

function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
