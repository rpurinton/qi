<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$session->header("Idea Submission");

?>

<style>
    /* Add any custom CSS styles here */
</style>

<div class="discroller">
    <label for="privacy-toggle" class="form-label">Privacy Mode:</label>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="privacy-toggle" name="privacy-toggle">
        <label class="form-check-label" for="privacy-toggle">Private</label>
        <label class="form-check-label" for="privacy-toggle">Public</label>
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