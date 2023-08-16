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
        transform: scale(2);
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
            <div class="col-auto">
                <label class="form-check-label" for="privacy-toggle">
                    <h3>Private</h3>
                </label>
            </div>
            <div class="col">
                <input class="form-check-input" type="checkbox" id="privacy-toggle" name="privacy-toggle">
            </div>
            <div class="col-auto">
                <label class="form-check-label" for="privacy-toggle">
                    <h3>Public</h3>
                </label>
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
    // Add any custom JavaScript code here
</script>

<?php
$session->footer([]);
?>