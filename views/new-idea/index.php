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
        border: 1px solid #000;
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
        border: 1px solid #000;
    }

    #voicetyping {
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
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
        border: 1px solid #000;
        border-radius: 10px;
        margin: 10px;
        margin-right: 10px;
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
    <div class="col-auto" style='display: flex; visibility: hidden; align-items: center; justify-content: center;'>
        <!-- dropdown box listing top 10 common public licenses -->
        <select id="license-dropdown" class="form-select form-control" aria-label="License" style='color:#f0f8ff; text-align: center; border: 1px solid black;'>
            <?php
            $result = $session->sql->query("SELECT * FROM `license_types`;");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['license_type_id'] . "'>" . $row['type_name'] . "</option>";
            }
            ?>
        </select>
    </div>
    <!-- make description text area fill the remaining space, take focus by default, and support multiline text entry with word wrapping and scrolling.  it should never grow beyond it's original size. -->
    <textarea id="description" name="description" rows="20" style="resize: vertical;" class="form-control control-group description" autofocus placeholder="Enter your idea here... Markdown support enabled!" required inputmode="text" x-webkit-speech></textarea>
    <button id="voicetyping" onclick="focusTextarea()"><i class="mdi mdi-microphone"></i></button>
    <div id="attachments" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <!-- Thumbnails of attachments will be dynamically generated here -->
    </div>
    <input type="file" id="fileInput" multiple style="display: none">
    <!-- Give hint to drag files to the text area to upload new attachments -->
    <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <label id="attachments-label" for="attachments"><i class="mdi mdi-arrow-up-bold-circle-outline" style='vertical-align:middle;'></i> Drag files here to upload attachments <i class='mdi mdi-arrow-up-bold-circle-outline' style='vertical-align: middle;'></i></label>
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
    const label = document.getElementById("attachments-label");
    const fileInput = document.getElementById("fileInput");

    label.addEventListener("click", () => {
        fileInput.click();
    });

    function upload($file) {
        console.log("uploading " + $file.name);
    }

    // when files have been selected in the fileInput, upload each file

    fileInput.addEventListener("change", () => {
        for (const file of fileInput.files) {
            upload(file);
        }
    });


    function focusTextarea() {
        document.getElementById("description").focus();
    }
    const privacyToggle = document.getElementById("privacy-toggle");
    const publicLabel = document.getElementById("public-label");
    const licenseDropdown = document.getElementById("license-dropdown");

    privacyToggle.addEventListener("change", function() {
        if (this.checked) {
            publicLabel.style.color = "#3366FF";
            publicLabel.style.textShadow = "0 0 2px #88AAFF";
            //display the dropdown
            licenseDropdown.style.visibility = "visible";
            // focus the license dropdown
            licenseDropdown.focus();
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

    // display a warning if there is unsaved text in the textbox if they attempt to navigate away from the page
    var form_submitted = false;
    window.addEventListener("beforeunload", function(e) {
        if (document.getElementById("description").value.length > 0 && !form_submitted) {
            e.preventDefault();
            e.returnValue = "";
        }
    });

    function final_submit() {
        // post the form to /views/new-idea/submit.php
        // cover the page with a semi-transparent div with a spinner in the middle while the form is submitting
        // the form, if successful will return a JSON object with the new idea_id
        // if the form submission fails, display an error message
        // if the form submission succeeds, redirect to /idea/{idea_id}

        // create the overlay
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(0,0,0,0.5)";
        overlay.style.zIndex = "1000";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.flexDirection = "column";
        // create the spinner
        const spinner = document.createElement("div");
        spinner.classList.add("spinner-border");
        spinner.style.width = "3rem";
        spinner.style.height = "3rem";
        spinner.style.color = "#f0f8ff";
        spinner.style.borderTopColor = "#f0f8ff";
        spinner.style.borderRightColor = "#f0f8ff";
        spinner.style.borderBottomColor = "#f0f8ff";
        spinner.style.borderLeftColor = "#000";
        spinner.style.borderRadius = "50%";
        spinner.style.animation = "spinner-border 0.75s linear infinite";
        // add the spinner to the overlay
        overlay.appendChild(spinner);
        // add the overlay to the page
        document.body.appendChild(overlay);

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
            .catch(error => {
                // display an error message
                alert(error);
                // remove the overlay
                document.body.removeChild(overlay);
            });
    }

    // client-side validation prior to form submission
    document.querySelector("button[type='submit']").addEventListener("click", function(e) {
        // prevent the form from submitting
        e.preventDefault();
        // check if the description is empty
        if (document.getElementById("description").value.length == 0) {
            // display an error message
            alert("You must enter a description before submitting your idea.");
            // exit the function
            return;
        }
        // check if the privacy toggle is checked
        if (privacyToggle.checked) {
            // check if the license dropdown is set to a valid value
            if (licenseDropdown.value == 0 || licenseDropdown.value == -1 || licenseDropdown.value == null || licenseDropdown.value == undefined) {
                // display an error message
                alert("You must select a license before submitting your idea.");
                // exit the function
                return;
            }
        }
        // check if the agree to TOS checkbox is checked
        if (!document.getElementById("agree-tos").checked) {
            // display an error message
            alert("You must agree to the Terms of Service before submitting your idea.");
            // exit the function
            return;
        }
        // check if the user is logged in
        if (loggedin == 'false') {
            // display an error message
            alert("You must be logged in to submit an idea.");
            // exit the function
            return;
        }
        // submit the form
        final_submit();
    });

    // when focused on the text area make Enter key or CTRL ENTER or SHIFT ENTER add a line feed (not submit the form)
    document.getElementById("description").addEventListener("keydown", function(e) {
        if (e.key == "Enter") {
            this.value += "\n";
            // prevent the form from submitting
            e.preventDefault();
        }
    });
</script>
<?php
$session->footer([]);
?>