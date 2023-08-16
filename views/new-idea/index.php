<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(true);
$session->header("Idea Submission");

?>

<style>
    /* Add any custom CSS styles here */

    .form-check-label {
        margin: 0;
        margin-right: 10px;
    }

    .form-check-input {
        margin: 0 !important;
        margin-right: 10px !important;
        margin-left: 10px !important;
    }

    .form-check-input[type="checkbox"] {
        transform: scale(1.25);
    }

    .control-group {
        padding-left: 0;
        margin: 0;
        margin-bottom: 10px;
        max-width: calc(100% - 10px);
        width: 100%;
        display: flex;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .form-select {
        position: relative;
        top: 0;
        left: 0;
        width: fit-content;
        height: 100%;
        background: transparent;
        border: none;
        outline: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        color: #f0f8ff;
        font-size: 100%;
        font-weight: bold;
        text-align-last: center;
        line-height: 100%;
    }

    .description {
        resize: none;
        overflow: auto;
        height: calc(100% - 240px);
        max-height: calc(100% - 240px);
        min-height: calc(100% - 240px);
        width: calc(100% - 20px);
        max-width: calc(100% - 20px);
        min-width: calc(100% - 20px);
        padding: 10px;
        margin: 10px;
        margin-right: 10px;
        border-radius: 10px;
    }
</style>

<div class="discroller">
    <div class="form-check form-switch control-group">
        <div class="row">
            <div class="col-auto" style='display: flex; align-items: center;'>
                <label class="form-check-label" for="privacy-toggle" style='font-size:100%; font-weight:bold;'>Private</label>
            </div>
            <div class="col" style='display: flex; align-items: center;'>
                <input class="form-check-input" type="checkbox" id="privacy-toggle" name="privacy-toggle">
            </div>
            <div class="col-auto" style='display: flex; align-items: center;'>
                <label id="public-label" class="form-check-label" for="privacy-toggle" style='font-size:100%; font-weight:bold;'>Public</label>
            </div>
        </div>
    </div>
    <div class="col-auto" id="license-dropdown" style='display: flex; visibility: hidden; align-items: center; justify-content: center;'>
        <!-- dropdown box listing top 10 common public licenses -->
        <select class="form-select form-control" aria-label="License" style='color:#f0f8ff; text-align: center;'>
            <?php
            $result = $session->sql->query("SELECT * FROM `license_types`;");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['license_type_id'] . "'>" . $row['type_name'] . "</option>";
            }
            ?>
        </select>
    </div>
    <!-- make description text area fill the remaining space, take focus by default, and support multiline text entry with word wrapping and scrolling.  it should never grow beyond it's original size. -->
    <textarea id="description" name="description" class="form-control control-group description" autofocus placeholder="Enter your idea here..." required></textarea>
    <div id="attachments" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <!-- Thumbnails of attachments will be dynamically generated here -->
    </div>
    <!-- Give hint to drag files to the text area to upload new attachments -->
    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <label for="attachments"><i class="mdi mdi-arrow-up-bold-circle-outline" style='vertical-align:middle;'></i> Drag files here to upload attachments <i class='mdi mdi-arrow-up-bold-circle-outline' style='vertical-align: middle;'></i></label>
    </div>
    <!-- Button to submit the form with the I agree to TOS above the button -->
    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <div class="form-check" style="width: 170px;">
            <!-- display checkbox required checked readonly -->
            <input class="form-check-input" type="checkbox" value="" id="agree-tos" name="agree-tos" required checked disabled>
            <label class="form-check-label" for="agree-tos">I agree to the <a target="_blank" href='/tos'>Terms of Service<a></label>
        </div>
        <button type="submit" class="btn btn-primary">Submit The Idea</button>
    </div>
</div>

<script>
    const privacyToggle = document.getElementById("privacy-toggle");
    const publicLabel = document.getElementById("public-label");
    const licenseDropdown = document.getElementById("license-dropdown");

    privacyToggle.addEventListener("change", function() {
        if (this.checked) {
            publicLabel.style.color = "#3366FF";
            publicLabel.style.textShadow = "0 0 2px #88AAFF";
            //display the dropdown
            licenseDropdown.style.visibility = "visible";
        } else {
            publicLabel.style.color = ""; // Reset to default color
            publicLabel.style.textShadow = ""; // Reset to default text shadow
            //hide the dropdown
            licenseDropdown.style.visibility = "hidden";
        }
    });
    <?php
    // force true false text
    echo ("const loggedin = '" . ($session->loggedin ? "true" : "false") . "';");
    ?>
    if (loggedin == 'false') {
        // disable the submit button
        document.querySelector("button[type='submit']").disabled = true;
        // disable the privacy toggle
        privacyToggle.disabled = true;
        // disable the license dropdown
        licenseDropdown.disabled = true;
        // disable the description textarea
        document.getElementById("description").disabled = true;
        // disable the agree to TOS checkbox
        document.getElementById("agree-tos").disabled = true;
        // disable the file upload
        document.getElementById("attachments").style.display = "none";
        // display a message to login
        document.getElementById("description").placeholder = "You must be logged in to submit an idea.";
    }
    <?php
    $session->footer([]);
    ?>