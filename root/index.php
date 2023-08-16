<?php

require_once(__DIR__ . "/../views/src/Session.php");
$session = new \RPurinton\qi\Session(true);
$session->header("𝗤𝘂𝗮𝗻𝘁𝘂𝗺 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀", true);
?>
<style>
    .discroller {
        width: 100%;
        height: 100%;
        overflow: scroll;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #111111;
    }

    .loginbuton {
        position: absolute;
        top: 0;
        right: 0;
        margin: 1rem;
    }

    .navbar-profile {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .navbar-profile-name {
        margin-left: 0.5rem;
        color: #fff;
    }

    .navbar-profile-name:hover {
        color: #ff0;
    }

    .mainlogo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .mainlogoimage {
        width: 272px;
        max-width: 80%;
        height: auto;
        margin: 1rem;
        margin-bottom: 2rem;
    }

    .mainiconimage {
        width: 75px;
        height: auto;
        margin: 1rem;
        margin-right: 0;
        margin-bottom: 2rem;
    }

    .linksfooter {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        margin: 0;
        width: 100%;
        justify-content: center
    }

    .linksfooter a {
        text-align: center;
        color: #fff;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        margin-left: 0.5rem;
        margin-right: 0.5rem;

    }

    .linksfooter a:hover {
        color: #ff0;
    }

    .copyrightfooter {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        margin: 0;
        min-width: 330px;
        text-align: center;
    }

    #search {
        display: none;
    }

    .linksleft {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        margin: 0;
    }

    .linksright {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        margin: 0;
    }

    .searchbox {
        display: flex;
        border: 1px solid #777;
        border-radius: 24px;
        max-width: 80%;
        width: 584px;
        min-height: 44px;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-start;
        margin: 0;
        align-content: center;
    }

    #searchinput {
        border: none;
        background-color: transparent;
        color: #fff;
        padding: 0.5rem;
        max-width: calc(100% - 44px);
    }

    .searchbox i {
        vertical-align: middle;
        flex-shrink: 0;
        padding-left: 0.666rem;
        padding-top: 4px;
    }

    .globalstats {
        max-width: 90%;
        width: 584px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        margin: 1rem;
    }

    .globalstats a {
        text-align: center;
        color: #fff;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }

    .globalstats a:hover {
        color: #ff0;
    }

    .flexgrow {
        transform: translateY(-108px);
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        max-width: calc(100% - 10px);
    }

    .flexspacer {
        flex-grow: 1;
    }
</style>
<div class="discroller">
    <div class="flexgrow">
        <div class="mainlogo">
            <img class="mainiconimage" src="/assets/images/icon/qi_icon.png" alt="𝗤𝘂𝗮𝗻𝘁𝘂𝗺"><img class="mainlogoimage" src="/assets/images/logo/qi_logo.png" alt="𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀">
        </div>
        <div class="searchbox">
            <i class="mdi mdi-magnify"></i>
            <input type="text" class="form-control" name="q" id="searchinput" autofocus autocomplete="off" placeholder="I have an idea..." />
        </div>
        <div class="globalstats">
            <?php
            extract($session->sql->single("SELECT COUNT(1) as `ideas` FROM `ideas`"));
            extract($session->sql->single("SELECT COUNT(1) as `users` FROM `discord_users`"));
            extract($session->sql->single("SELECT COUNT(1) as `relationships` FROM `idea_relations`"));
            extract($session->sql->single("SELECT COUNT(1) as `shares` FROM `idea_shares`"));
            extract($session->sql->single("SELECT COUNT(1) as `favorites` FROM `favorites`"));
            extract($session->sql->single("SELECT COUNT(1) as `comments` FROM `feedback`"));
            extract($session->sql->single("SELECT sum(`views`) as `visits` FROM `ip`"));
            $ideas = number_format($ideas, 0, '.', ',');
            $users = number_format($users, 0, '.', ',');
            $relationships = number_format($relationships, 0, '.', ',');
            $shares = number_format($shares, 0, '.', ',');
            $favorites = number_format($favorites, 0, '.', ',');
            $comments = number_format($comments, 0, '.', ',');
            $visits = number_format($visits, 0, '.', ',');
            echo ("<a href='/ideas'>$ideas Ideas</a>");
            echo ("<a href='/users'>$users Users</a>");
            echo ("<a href='/relationships'>$relationships Relationships</a>");
            echo ("<a href='/shares'>$shares Shares</a>");
            echo ("<a href='/favorites'>$favorites Favorites</a>");
            echo ("<a href='/comments'>$comments Comments</a>");
            echo ("<a href='/visits'>$visits Visits</a>");
            ?>
        </div>
    </div>
    <div class="linksfooter" style='margin-bottom: 25px;'>
        <div class="linksleft">
            <a href="https://discord.gg/F685FVEXmU" target="_blank">Contact</a>
            <a href="https://discord.gg/F685FVEXmU" target="_blank">Support</a>
        </div>
        <div class="copyrightfooter">
            𝗤𝘂𝗮𝗻𝘁𝘂𝗺 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀 &copy;2023 Laozi 老子
        </div>
        <div class="linksright">
            <a href="/privacy" target="_blank">Privacy</a>
            <a href="/tos" target="_blank">Terms</a>
        </div>
    </div>
    <script>

    </script>
    <?php
    $session->footer();
