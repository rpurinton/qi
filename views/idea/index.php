<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/../src/Session.php');
$session = new Session(false);
$idea_id = explode('/', $_SERVER['REQUEST_URI'])[2];
extract($session->sql->single("SELECT min(`idea_id`) as `min_id`, max(`idea_id`) as `max_id` FROM `ideas`"));
if (!is_numeric($idea_id) || $idea_id < $min_id || $idea_id > $max_id) $session->redirect('/search');
$result = $session->sql->single("SELECT
        *,
        `license_types`.`type_name` as `license_type`,
        `discord_users`.`discord_global_name`,
        `discord_users`.`discord_avatar`
    FROM `ideas`
    LEFT JOIN `license_types` ON `ideas`.`license_type_id` = `license_types`.`license_type_id`
    LEFT JOIN `discord_users` ON `ideas`.`discord_id` = `discord_users`.`discord_id`
    WHERE `idea_id` = '$idea_id'");
if (!$result) $session->redirect('/search');
extract($result);
require_once(__DIR__ . '/../src/Utilities.php');
$utilities = new Utilities();
$description = $utilities->markdown_to_html($description);
$visibility = $privacy_status ? 'Public' : 'Private';
$session->header("Idea #$idea_id");
?>
<style>

</style>
<div class="discroller">
    <div style="margin: 5px; padding: 5px; border: 1px solid black; border-radius: 6px;">
        <div class="flex-container" style='justify-content: start; align-items: center;'>
            <h1>Idea #<?= $idea_id ?></h1>
            <p>by <a href="/user/<?= $discord_id ?>"><img src='https://cdn.discordapp.com/avatars/<?= $discord_id ?>/<?= $discord_avatar ?>.png' style='width: 32px; height: 32px; border-radius: 50%; vertical-align: middle; margin-right: 5px;'><?= $discord_global_name ?></a>
        </div>
        <table style="padding: 2px;">
            <tr>
                <td style="padding: 2px;">Created</td>
                <td style="padding: 2px;"><?= $date_of_creation ?></td>
            </tr>
            <tr>
                <td style="padding: 2px;">Updated</td>
                <td style="padding: 2px;"><?= $date_of_last_update ?></td>
            </tr>
            <tr>
                <td style="padding: 2px;">Visibility</td>
                <td style="padding: 2px;"><?= $visibility ?></td>
            <tr>
                <td style="padding: 2px;">License</td>
                <td style="padding: 2px;"><a href='/license/<?= $license_type_id ?>'><?= $license_type ?></a></td>
        </table>
        <div class="idea">
            <div class="idea-tags">
                <div class="idea-tags">
                    <?php
                    $tags = $session->sql->query("SELECT `tag` FROM `tags` WHERE `idea_id` = $idea_id");
                    while ($tag = $tags->fetch_assoc()) echo "<a href='/tag/{$tag['tag']}' class='idea-tag'>{$tag['tag']}</a>";
                    ?>
                </div>
            </div>
            <div class="idea-description"><?= $description ?></div>
        </div>
    </div>
</div>
<script>

</script>
<?php
$session->footer();
