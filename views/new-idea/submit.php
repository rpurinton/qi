<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(true);
if (!$session->loggedin) exit();

// get the $_POST variables and perform server side input validation
$description = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);
$privacy_toggle = filter_input(INPUT_POST, 'privacy-toggle', FILTER_VALIDATE_BOOLEAN);
$license_dropdown = filter_input(INPUT_POST, 'license-dropdown', FILTER_SANITIZE_NUMBER_INT);
$agree_tos = filter_input(INPUT_POST, 'agree-tos', FILTER_VALIDATE_BOOLEAN);
// VALIDATE
if (!$description) {
    echo json_encode(array("message" => "Please enter a description."));
    exit();
}
if (!$agree_tos) {
    echo json_encode(array("message" => "Please agree to the Terms of Service."));
    exit();
}
extract($session->sql->single("SELECT count(1) as `count` FROM `license_types` WHERE `license_type_id` = '$license_dropdown'"));
if ($privacy_toggle && $count == 0) {
    echo json_encode(array("message" => "Please select a valid license."));
    exit();
}
// ESCAPE
$description = $session->sql->escape($description);
// privacy toggle is a bool, true if public false if private force it to be either 0 or 1
$privacy_toggle = $privacy_toggle ? 'public' : 'private';
$license_dropdown = $session->sql->escape($license_dropdown);
// INSERT
$response["idea_id"] = $session->sql->insert("INSERT INTO `ideas`
        (`description`, `discord_id`, `privacy_status`, `version_number`, `license_type_id`)
    VALUES
        ('$description', '$session->user_id', '$privacy_toggle', '1', '$license_dropdown')
");
if ($response["idea_id"]) {
    $response["message"] = "Idea submitted successfully.";
} else {
    $response["message"] = "Idea submission failed.";
}
echo (json_encode($response));
