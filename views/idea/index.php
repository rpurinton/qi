<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$idea_id = explode('/', $_SERVER['REQUEST_URI'])[2];
extract($session->sql->single("SELECT min(`idea_id`) as `min_id`, max(`idea_id`) as `max_id` FROM `ideas`"));
if (!is_numeric($idea_id) || $idea_id < $min_id || $idea_id > $max_id) $session->redirect('/search');
$result = $session->sql->single("SELECT * FROM `ideas` WHERE `idea_id` = '$idea_id'");
if (!$result) $session->redirect('/search');
extract($result);
require_once(__DIR__ . '/../src/Utilities.php');
$utilities = new Utilities();
$description = $utilities->markdown_to_html($description);
$session->header("Idea #$idea_id");
?>
<style>

</style>
<div class="discroller">
    <div class="flex-container">
        <h1>Idea #<?= $idea_id ?></h1>
        <div class="idea">
            <div class="idea-description"><?= $description ?></div>
            <div class="idea-tags">
                <?php
                $tags = $session->sql->query("SELECT `tag` FROM `tags` WHERE `idea_id` = $idea_id");
                while ($tag = $tags->fetch_assoc()) echo "<a href='/tag/{$tag['tag']}' class='idea-tag'>{$tag['tag']}</a>";
                ?>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php
$session->footer();
