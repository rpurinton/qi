<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
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
        transform: scale(1.5);
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
</style>

<div class="discroller">
    <div class="form-check form-switch control-group">
        <div class="row">
            <div class="col-auto" style='display: flex; align-items: center;'>
                <label class="form-check-label" for="privacy-toggle" style='font-size:150%; font-weight:bold;'>Private</label>
            </div>
            <div class="col" style='display: flex; align-items: center;'>
                <input class="form-check-input" type="checkbox" id="privacy-toggle" name="privacy-toggle">
            </div>
            <div class="col-auto" style='display: flex; align-items: center;'>
                <label id="public-label" class="form-check-label" for="privacy-toggle" style='font-size:150%; font-weight:bold;'>Public</label>
            </div>
            <div class="col-auto" style='display: flex; align-items: center;'>
                <i class="fas fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Public ideas will be visible to all users. Private ideas will only be visible to you."></i>
            </div>
        </div>
    </div>

    <textarea id="description" name="description" rows="10" class="form-control" placeholder="Enter your idea description..."></textarea>

    <h2>Attachments</h2>
    <div id="attachments">
        <!-- Thumbnails of attachments will be dynamically generated here -->
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="agree-tos" name="agree-tos" required>
        <label class="form-check-label" for="agree-tos">I agree to the Terms of Service</label>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</div>

<script>
    const privacyToggle = document.getElementById("privacy-toggle");
    const publicLabel = document.getElementById("public-label");

    privacyToggle.addEventListener("change", function() {
        if (this.checked) {
            publicLabel.style.color = "#3366FF";
            publicLabel.style.textShadow = "0 0 2px #88AAFF";
        } else {
            publicLabel.style.color = ""; // Reset to default color
            publicLabel.style.textShadow = ""; // Reset to default text shadow
        }
    });
</script>

<?php
$session->footer([]);
?>