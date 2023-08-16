<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$session->header("Idea ###");
?>
<style>

</style>
<div class="discroller">
    Individual Idea Page
</div>
<script>

</script>
<?php
$session->footer();
