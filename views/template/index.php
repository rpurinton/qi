<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$session->header("Title");
?>
<style>

</style>
<div class="discroller">

</div>
<script>

</script>
<?php
$session->footer();
