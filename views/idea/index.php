<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$idea_id = explode('/', $_SERVER['REQUEST_URI'])[2];
extract($session->sql->single("SELECT min(`idea_id`) as `min_id`, max(`idea_id`) as `max_id` FROM `ideas`"));
if (!is_numeric($idea_id) || $idea_id < $min_id || $idea_id > $max_id) {
    $session->redirect('/search');
}
$session->header("Idea $idea_id");
?>
<style>

</style>
<div class="discroller">
    <h1>Idea #<?= $idea_id ?></h1>
</div>
<script>

</script>
<?php
$session->footer();
