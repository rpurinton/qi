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
            <img class="mainlogoimage" src="/assets/images/logo/logo-transparent-white-898x303.png" alt="𝗤𝘂𝗮𝗻𝘁𝘂𝗺 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀">
        </div>
        <div class="searchbox">
            <i class="mdi mdi-magnify"></i>
            <input type="text" class="form-control" name="q" id="searchinput" autofocus autocomplete="off" />
        </div>
        <div class="globalstats">
        </div>
    </div>
    <div class="linksfooter">
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
