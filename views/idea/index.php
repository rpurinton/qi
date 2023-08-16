<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$idea_id = explode('/', $_SERVER['REQUEST_URI'])[2];
extract($session->sql->single("SELECT min(`idea_id`) as `min_id`, max(`idea_id`) as `max_id` FROM `ideas`"));
if (!is_numeric($idea_id) || $idea_id < $min_id || $idea_id > $max_id) $session->redirect('/search');
$result = $session->sql->single("SELECT
        *,
        `license_types`.`type_name` as `license_type`
    FROM `ideas`
    LEFT JOIN `license_types` ON `ideas`.`license_type_id` = `license_types`.`license_type_id`
    WHERE `idea_id` = '$idea_id'");
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
    <div style="margin: 5px; padding: 5px; border: 1px solid black; border-radius: 10px;">
        <h1>Idea #<?= $idea_id ?></h1>
        <div class="idea">
            <div class="idea-description"><?= $description ?></div>
            <div class="idea-license">
                <h2>License</h2>
                <div class="idea-license-type"><?= $license_type ?></div>
            </div>
            <div class="idea-tags">
                <h2>Tags</h2>
                <div class="idea-tags">
                    <?php
                    $tags = $session->sql->query("SELECT `tag` FROM `tags` WHERE `idea_id` = $idea_id");
                    while ($tag = $tags->fetch_assoc()) echo "<a href='/tag/{$tag['tag']}' class='idea-tag'>{$tag['tag']}</a>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php
$session->footer();
