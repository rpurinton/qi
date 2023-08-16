<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$session->header("Favorites");
?>
<style>

</style>
<div class="discroller">
    Placeholder for Favorites
</div>
<script>

</script>
<?php
$session->footer();
