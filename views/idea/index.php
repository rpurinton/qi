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
$session->header("Idea #$idea_id");
?>
<style>

</style>
<div class="discroller">
    <h1>Idea #<?= $idea_id ?></h1>
    <div class="idea">
        <div class="idea-description"><?= $description ?></div>
        <div class="idea-tags">
            <?php
            $tags = $sql->query("SELECT `tag` FROM `tags` WHERE `idea_id` = $idea_id");
            while ($tag = $tags->fetch_assoc()) echo "<div class='idea-tag'>{$tag['tag']}</div>";
            ?>
        </div>
        <div class="idea-votes">
            <div class="idea-vote" data-vote="up" data-idea="<?= $idea_id ?>">Up</div>
            <div class="idea-vote" data-vote="down" data-idea="<?= $idea_id ?>">Down</div>
        </div>
    </div>
</div>
<script>

</script>
<?php
$session->footer();
