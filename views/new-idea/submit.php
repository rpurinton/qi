<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(true);
if (!$session->loggedin) exit();

/*
The submitting javascript looks like this:
      // create the form data
        const formData = new FormData();
        // add the description to the form data
        formData.append("description", document.getElementById("description").value);
        // add the privacy toggle to the form data
        formData.append("privacy-toggle", privacyToggle.checked);
        // add the license dropdown to the form data
        formData.append("license-dropdown", licenseDropdown.value);
        // add the agree to TOS checkbox to the form data
        formData.append("agree-tos", document.getElementById("agree-tos").checked);

        // post the form data to /views/new-idea/submit.php
        fetch("/views/new-idea/submit.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // check if the response was successful
                if (data.idea_id) {
                    // disable the redirection blocker
                    window.onbeforeunload = null;
                    // redirect to /idea/{idea_id}
                    form_submitted = true;
                    window.location.href = "/idea/" + data.idea_id;
                } else {
                    // display an error message
                    alert(data.message);
                    // remove the overlay
                    document.body.removeChild(overlay);
                }
            })
        sql schema: CREATE TABLE `ideas` (
  `idea_id` bigint(20) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_of_creation` datetime DEFAULT current_timestamp(),
  `date_of_last_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `discord_id` bigint(20) NOT NULL,
  `privacy_status` enum('public','private') DEFAULT NULL,
  `parent_idea_id` bigint(20) DEFAULT NULL,
  `version_number` int(11) DEFAULT NULL,
  `description_token_count` int(11) DEFAULT NULL,
  `total_token_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
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
extract($session->sql->single("SELECT min(`license_type_id`) as `min_id`, max(`license_type_id`) as `max_id` FROM `license_types`"));
if ($privacy_toggle && ($license_dropdown < $min_id || $license_dropdown > $max_id)) {
    echo json_encode(array("message" => "Please select a valid license."));
}
// ESCAPE
$description = $session->sql->escape($description);
// privacy toggle is a bool, true if public false if private force it to be either 0 or 1
$privacy_toggle = $privacy_toggle ? 1 : 0;
$license_dropdown = $session->sql->escape($license_dropdown);
// INSERT
$response["idea_id"] = $session->sql->insert("INSERT INTO `ideas`
        (`description`, `discord_id`, `privacy_status`, `version_number`)
    VALUES
        ('$description', '$session->user_id', '$privacy_toggle', '1');
");
if ($response["idea_id"]) {
    $response["message"] = "Idea submitted successfully.";
} else {
    $response["message"] = "Idea submission failed.";
}
echo (json_encode($response));
