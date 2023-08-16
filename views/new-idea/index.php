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
    <h1>New Idea Submission</h1>

    <label for="privacy-toggle">Privacy Mode:</label>
    <input type="checkbox" id="privacy-toggle" name="privacy-toggle">

    <textarea id="description" name="description" rows="10" placeholder="Enter your idea description..."></textarea>

    <h2>Attachments</h2>
    <div id="attachments">
        <!-- Thumbnails of attachments will be dynamically generated here -->
    </div>

    <label for="agree-tos">I agree to the Terms of Service:</label>
    <input type="checkbox" id="agree-tos" name="agree-tos" required>

    <button type="submit">Submit</button>
</div>

<script>
    // Add any custom JavaScript code here
</script>

<?php
$session->footer([]);
?>