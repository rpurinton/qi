<?php

namespace Rpurinton\qi;

try {
    require_once(__DIR__ . "/../src/Session.php");
    $session = new Session;
    $show_inactive = isset($_POST['inactive']) && $_POST['inactive'] == "true";
    $wildcard = isset($_POST['wildcard']) && $_POST['wildcard'] == "true";

    $characters = isset($_POST['characters']) && $_POST['characters'] == "true";
    $clans = isset($_POST['clans']) && $_POST['clans'] == "true";
    $servers = isset($_POST['servers']) && $_POST['servers'] == "true";
    $regions = isset($_POST['regions']) && $_POST['regions'] == "true";

    echo ("<div style='display: flex; justify-content: space-evenly; width: 100%;' class='toggle-swtiches'>\n");
    if ($characters) {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"characterssearch(false);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='characters' checked>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-account'></i></span>\n");
        echo ("</div>\n");
    } else {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"characterssearch(true);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='characters'>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-account'></i></span>\n");
        echo ("</div>\n");
    }

    if ($clans) {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"clanssearch(false);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='clans' checked>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-account-group'></i></span>\n");
        echo ("</div>\n");
    } else {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"clanssearch(true);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='clans'>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-account-group'></i></span>\n");
        echo ("</div>\n");
    }

    if ($servers) {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"serverssearch(false);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='servers' checked>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-castle'></i></span>\n");
        echo ("</div>\n");
    } else {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"serverssearch(true);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='servers'>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-castle'></i></span>\n");
        echo ("</div>\n");
    }

    if ($regions) {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"regionssearch(false);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='regions' checked>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-map-marker'></i></span>\n");
        echo ("</div>\n");
    } else {
        echo ("<div style='display: flex; align-items: center; text-align: center;' onclick=\"regionssearch(true);\">\n");
        echo ("<label class='switch'>\n");
        echo ("<input type='checkbox' id='regions'>\n");
        echo ("<span class='slider'></span>\n");
        echo ("</label>\n");
        echo ("<span style='font-size: 150%;' class='switch-label'><i class='mdi mdi-map-marker'></i></span>\n");
        echo ("</div>\n");
    }
    echo ("</div>\n");

    echo ("<div style='margin-left: 100px; width: 100%;' class='form-check form-switch'>\n");
    if ($wildcard) echo ("<input class='form-check-input' type='checkbox' id='wildcard' onclick=\"wildcardsearch(false);\" name='wildcard' checked>\n");
    else echo ("<input class='form-check-input' type='checkbox' id='wildcard' onclick=\"wildcardsearch(true);\" name='wildcard'>\n");
    echo ("<label class='form-check-label' for='wildcard'>Usar pesquisa avançada</label>\n</div>\n");
    echo ("<div style='margin-left: 100px; width: 100%;' class='form-check form-switch'>\n");
    if ($show_inactive) echo ("<input class='form-check-input' type='checkbox' id='inactive' onclick=\"inactivesearch(false);\" name='inactive' checked>\n");
    else echo ("<input class='form-check-input' type='checkbox' id='inactive' onclick=\"inactivesearch(true);\" name='inactive'>\n");
    echo ("<label class='form-check-label' for='inactive'>Mostrar resultados inativos</label>\n</div>\n");
    if (!$characters && !$clans && !$servers && !$regions) {
        // you must select atleast one search type
        echo ("<pre id='resultcount' style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>nenhum tipo de pesquisa selecionado</pre>");
        exit;
    }

    if (!isset($_POST['query'])) $session->error(403);
    $search = $_POST['query'];
    $search = $session->sql->escape($search);
    extract($session->sql->single("SELECT max(`id`) as `dataset_id` FROM `datasets` WHERE `complete` = '1'"));
    $query = "SELECT * FROM (\n";
    $new = true;
    if ($characters) {
        $new = false;
        $query .= "SELECT
                'character' AS `type`,
                `characters`.`id` AS `char_id`,
                `characters`.`name` AS `char_name`,
                `characters`.`class_id` AS `class_id`,
                `classes`.`name` AS `class_name`,
                `characters`.`clan_id` AS `clan_id`,
                `clans`.`name` AS `clan_name`,
                `characters`.`server_id` AS `server_id`,
                `servers`.`name` AS `server_name`,
                `characters`.`region_id` AS `region_id`,
                `regions`.`name` AS `region_name`,\n";
        if ($show_inactive) $query .= " '$dataset_id' - `characters`.`most_recent` AS `inactive`,";
        $query .= "
                `characters`.`power` AS `power`
            FROM
                `characters`
                INNER JOIN `classes` ON `characters`.`class_id` = `classes`.`id`
                INNER JOIN `clans` ON `characters`.`clan_id` = `clans`.`id`
                INNER JOIN `servers` ON `characters`.`server_id` = `servers`.`id`
                INNER JOIN `regions` ON `characters`.`region_id` = `regions`.`id`
            WHERE\n";
        if (!$wildcard) $query .= " (`characters`.`name` LIKE '%$search%')\n";
        else $query .= " MATCH (`characters`.`name`) AGAINST ('*$search*' IN BOOLEAN MODE)\n";
        if (!$show_inactive) $query .= " AND `characters`.`most_recent` >= '$dataset_id'\n";
    }
    if ($clans) {
        if (!$new) $query .= "UNION ALL\n";
        $new = false;
        $query .= "SELECT
                'clan' AS `type`,
                NULL AS `char_id`,
                NULL AS `char_name`,
                NULL AS `class_id`,
                NULL AS `class_name`,
                `clans`.`id` AS `clan_id`,
                `clans`.`name` AS `clan_name`,
                `clans`.`server_id` AS `server_id`,
                `servers`.`name` AS `server_name`,
                `clans`.`region_id` AS `region_id`,
                `regions`.`name` AS `region_name`,\n";
        if ($show_inactive) $query .= " '$dataset_id' - `clans`.`most_recent` AS `inactive`,";
        $query .= "
                `clans`.`power` AS `power`
            FROM
                `clans`
                INNER JOIN `servers` ON `clans`.`server_id` = `servers`.`id`
                INNER JOIN `regions` ON `clans`.`region_id` = `regions`.`id`
            WHERE\n";
        if (!$wildcard) $query .= " (`clans`.`name` LIKE '%$search%')\n";
        else $query .= " MATCH (`clans`.`name`) AGAINST ('*$search*' IN BOOLEAN MODE)\n";
        if (!$show_inactive) $query .= " AND `clans`.`most_recent` >= '$dataset_id'\n";
    }
    if ($servers) {
        if (!$new) $query .= "UNION ALL\n";
        $new = false;
        $query .= "SELECT
                'server' AS `type`,
                NULL AS `char_id`,
                NULL AS `char_name`,
                NULL AS `class_id`,
                NULL AS `class_name`,
                NULL AS `clan_id`,
                NULL AS `clan_name`,
                `servers`.`id` AS `server_id`,
                `servers`.`name` AS `server_name`,
                `servers`.`region_id` AS `region_id`,
                `regions`.`name` AS `region_name`,\n";
        if ($show_inactive) $query .= " `servers`.`legacy` AS `inactive`,";
        $query .= "
                 `servers`.`power` AS `power`
            FROM
                `servers`
            INNER JOIN `regions` ON `servers`.`region_id` = `regions`.`id`
            WHERE\n";
        if (!$wildcard) $query .= "((`servers`.`name` LIKE '%$search%') OR (`servers`.`aka` LIKE '%$search%'))\n";
        else $query .= " MATCH (`servers`.`name`, `servers`.`aka`) AGAINST ('*$search*' IN BOOLEAN MODE)\n";
        if (!$show_inactive) $query .= " AND `servers`.`legacy` = '0'\n";
    }
    if ($regions) {
        if (!$new) $query .= "UNION ALL\n";
        $new = false;
        $query .= "SELECT
                'region' AS `type`,
                NULL AS `char_id`,
                NULL AS `char_name`,
                NULL AS `class_id`,
                NULL AS `class_name`,
                NULL AS `clan_id`,
                NULL AS `clan_name`,
                NULL AS `server_id`,
                NULL AS `server_name`,
                `regions`.`id` AS `region_id`,
                `regions`.`name` AS `region_name`,\n";
        if ($show_inactive) $query .= "`regions`.`legacy` AS `inactive`,";
        $query .= "
                `regions`.`power` AS `power`
            FROM
                `regions`
            WHERE\n";
        if (!$wildcard) $query .= "(`regions`.`name` LIKE '%$search%')\n";
        else $query .= " MATCH (`regions`.`name`) AGAINST ('*$search*' IN BOOLEAN MODE)\n";
        if (!$show_inactive) $query .= " AND `regions`.`legacy` = '0'\n";
    }
    $query .= ") As T1  ORDER BY `power` DESC";
    $result = $session->sql->query($query);
    if (!$result) $session->error(500);
    $total_result = $result->num_rows;
    switch ($total_result) {
        case 0:
            break;
        case 1:
            echo ("<pre id='resultcount' style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>encontrado 1 resultado</pre>");
            break;
        default:
            echo ("<pre id='resultcount' style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>encontrados $total_result resultados</pre>");
            break;
    }
    if ($result->num_rows == 0) {
        echo ("<pre style='margin-left: 5px; text-align: center; width: calc(100% - 10px);'>Nenhum resultado encontrado</pre>");
    } else {
        echo ("<div class='table-responsive' style='width: 100%;'>");
        echo ("</div");
    }
} catch (\Exception $e) {
    print_r($e);
}
