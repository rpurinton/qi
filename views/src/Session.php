<?php

namespace Rpurinton\qi;

class Session implements \SessionHandlerInterface
{
    public $insecure = false;
    public $date = null;
    public $ip_id = null;
    public $ip = array();
    public $name = null;
    public $id = null;
    public $session = array();
    public $user_id = null;
    public $user = array();
    public $loggedin = false;
    public $valid = false;
    public $sql = null;
    public $favorites = ["region" => [], "server" => [], "clan" => [], "character" => []];
    public $lang = null;

    public function __construct($insecure = false)
    {
        $this->insecure = $insecure;
        require_once(__DIR__ . "/SessionSqlClient.php");
        $this->sql = new SessionSqlClient("qi");
        session_set_save_handler(
            $this->open(...),
            $this->close(...),
            $this->read(...),
            $this->write(...),
            $this->destroy(...),
            $this->gc(...)
        );
        register_shutdown_function('session_write_close');
        $this->name = session_name("qi");
        session_set_cookie_params(99999999, "/", "discommand.com");
        session_start();
        $this->date = date("Y-m-d");
        $this->refresh();
        return $this;
    }

    public function refresh()
    {
        $this->user_id = $this->get_user_id();
        $this->ip_id = $this->get_ip_id();
        $this->id = $this->get_id();
        $this->get_favorites();
        $this->audit();
        if (!$this->insecure) {
            if (!$this->loggedin) $this->redirect("https://qi.discommand.com/");
            else $this->validate();
        }
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $result = $this->sql->query("SELECT `data` FROM `sessions_php` WHERE `id` = '$id' LIMIT 0,1");
        if ($this->sql->count($result)) {
            return $this->sql->assoc($result)["data"];
        }
        return "";
    }

    public function write(string $id, string $data): bool
    {
        $access = time();
        if ($this->sql->query("REPLACE INTO `sessions_php` (`id`,`access`,`data`) VALUES ('$id','$access', '$data')")) {
            return true;
        }
        return false;
    }

    public function destroy(string $id): bool
    {
        if ($this->sql->query("DELETE FROM `sessions_php` WHERE `id` = '$id'")) {
            return true;
        }
        return false;
    }

    public function close(): bool
    {
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $stale = time() - $max_lifetime;
        if ($this->sql->query("DELETE FROM `sessions_php` WHERE `access` < '$stale'")) {
            return true;
        }
        return false;
    }

    public function generate_token()
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result = "";
        while (strlen($result) < 128) {
            $result .= $chars[rand(0, 61)];
        }
        return $result;
    }

    private function get_ip_id()
    {
        if (!isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $this->sql->query("UPDATE `ip` SET `views` = `views` + 1 WHERE `id` = 0");
            $this->ip = $this->sql->single("SELECT * FROM `ip` WHERE `id` = 0");
            $this->sql->query("INSERT INTO `ip_history` (`ip_id`,`date`) VALUES ('0','{$this->date}') ON DUPLICATE KEY UPDATE `views` = `views` + 1");
            return 0;
        }
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];

        $lat = 0;
        if (isset($_SERVER["HTTP_CF_IPLONGITUDE"])) $lat = $_SERVER["HTTP_CF_IPLONGITUDE"];

        $lon = 0;
        if (isset($_SERVER["HTTP_CF_IPLATITUDE"])) $lon = $_SERVER["HTTP_CF_IPLATITUDE"];

        $city = "";
        if (isset($_SERVER["HTTP_CF_IPCITY"])) $city = $this->sql->escape($_SERVER["HTTP_CF_IPCITY"]);

        $country = "";
        if (isset($_SERVER["HTTP_CF_IPCOUNTRY"])) $country = $this->sql->escape($_SERVER["HTTP_CF_IPCOUNTRY"]);

        $region = "";
        if (isset($_SERVER["HTTP_CF_IPCONTINENT"])) $region = $this->sql->escape($_SERVER["HTTP_CF_IPCONTINENT"]);

        $language = "";
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) $language = $this->sql->escape($_SERVER["HTTP_ACCEPT_LANGUAGE"]);

        $user_agent = "";
        if (isset($_SERVER["HTTP_USER_AGENT"])) $user_agent = $this->sql->escape($_SERVER["HTTP_USER_AGENT"]);

        $this->sql->query("INSERT INTO `ip` (`ip`,`last_user_id`,`lat`,`lon`,`city`,`country`,`region`,`language`,`user_agent`)
		VALUES ('$ip','{$this->user_id}','$lat','$lon','$city','$country','$region','$language','$user_agent')
		ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`),
		`last_user_id` = '{$this->user_id}',
		`lat` = '$lat',
		`lon` = '$lon',
		`city` = '$city',
		`country` = '$country',
		`region` = '$region',
		`language` = '$language',
		`user_agent` = '$user_agent',
		`views` = `views`+1");
        $insert_id = $this->sql->insert_id();
        $this->ip = $this->sql->single("SELECT * FROM `ip` WHERE `id` = '$insert_id'");
        $this->sql->query("INSERT INTO `ip_history` (`ip_id`,`date`,`user_id`) VALUES ('$insert_id','{$this->date}','{$this->user_id}') ON DUPLICATE KEY UPDATE `views` = `views` + 1");
        if ($this->ip["banned"]) {
            $this->error("403");
        }
        return $insert_id;
    }

    private function get_id()
    {
        $id = session_id();
        $language = "";
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $language = $this->sql->escape($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        }
        $user_agent = "";
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $user_agent = $this->sql->escape($_SERVER["HTTP_USER_AGENT"]);
        }
        $this->sql->query("INSERT INTO `sessions_app` (`session_id`,`ip_id`,`user_id`,`user_agent`)
		VALUES ('$id','{$this->ip_id}','{$this->user_id}','$user_agent')
		ON DUPLICATE KEY UPDATE
			`ip_id` = '{$this->ip_id}',
			`views` = `views`+1,
			`language` = '$language',
			`user_agent` = '$user_agent'");
        $this->session = $this->sql->single("SELECT * FROM `sessions_app` WHERE `session_id` = '$id'");
        return $id;
    }

    public function user_id()
    {
        $id = session_id();
        $result = $this->sql->query("SELECT `user_id` FROM `sessions_app` WHERE `session_id` = '$id'");
        if (!$this->sql->count($result)) {
            return 0;
        }
        return $this->sql->assoc($result)["user_id"];
    }

    private function get_user_id()
    {
        $discord_id = $this->user_id();
        if (!$discord_id) {
            return 0;
        }
        $this->loggedin = true;
        $this->sql->query("UPDATE `discord_users` SET `views` = `views` + 1 WHERE `discord_id` = '$discord_id'");
        $this->user = $this->sql->single("SELECT * FROM `discord_users` WHERE `discord_id` = '$discord_id'");
        if ($this->user["discord_avatar"] != "") $this->user["discord_avatar"] = "https://cdn.discordapp.com/avatars/$discord_id/" . $this->user["discord_avatar"] . ".png";
        else $this->user["discord_avatar"] = "https://cdn.discordapp.com/embed/avatars/" . ($discord_id % 5) . ".png";
        return $discord_id;
    }

    private function get_favorites()
    {
        $this->favorites = ["region" => [], "server" => [], "clan" => [], "character" => []];
        if ($this->user_id) {
            $result = $this->sql->query("SELECT * FROM `favorites` WHERE `user_id` = '{$this->user_id}'");
            while ($row = $this->sql->assoc($result)) $this->favorites[$row["type"]][] = $row["favorite_id"];
        }
    }

    private function audit()
    {
        $table = "audits_" . $this->date;
        if (isset($_SERVER["REQUEST_URI"])) {
            $request_uri = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        } else {
            global $argv;
            $request_uri = $_SERVER["PWD"] . "/" . implode(" ", $argv);
        }
        $request_uri = $this->sql->escape($request_uri);
        $this->sql->query("INSERT INTO `$table` (`ip_id`,`user_id`,`session_id`,`request_uri`) VALUES ('{$this->ip_id}','{$this->user_id}','{$this->id}','$request_uri')");
    }

    private function validate()
    {
        if (isset($this->user["banned"]) && $this->user["banned"]) $this->error("403");
        $this->valid = true;
    }

    public function error($code)
    {
        http_response_code($code);
        include(__DIR__ . "/../errors/error-$code.html");
        exit();
    }

    public function logout()
    {
        $id = $this->id;
        $this->sql->query("UPDATE `sessions_app` SET `user_id` = 0 WHERE `session_id` = '$id'");
        $this->redirect("https://qi.discommand.com/");
    }

    public function header($title = "𝗤𝘂𝗮𝗻𝘁𝘂𝗺 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀", $side_nav = true)
    {
        require_once(__DIR__ . "/includes/header.php");
        $header($title, $side_nav);
    }

    public function footer($customjs = [])
    {
        require_once(__DIR__ . "/includes/footer.php");
    }

    public function redirect($location)
    {
        header("Location: $location", true, 302);
        exit();
    }

    public function human($num)
    {
        if ($num < 1000) return $num;
        if ($num < 1000000) return number_format($num / 1000, 1) . "K";
        if ($num < 1000000000) return number_format($num / 1000000, 2) . "M";
        if ($num < 1000000000000) return number_format($num / 1000000000, 3) . "B";
        return number_format($num / 1000000000000, 3) . "T";
    }

    public function percent($num)
    {
        return " (" . number_format($num * 100, 0) . "%)";
    }

    public function rank_emoji($rank)
    {
        switch ($rank) {
            case 1:
                return "🥇";
            case 2:
                return "🥈";
            case 3:
                return "🥉";
            default:
                return number_format($rank, 0, ".", ",") . "º";
        }
    }

    public function card($title = null, $body = null, $footer = null, $flex_item = null)
    {
        $html = $flex_item ? "<div class='card flex-item'>\n" : "<div class='card'>\n";
        $html = $title == "" ? $html : $html . "\t<div class='card-header'>\n\t\t<h1 style='width: 100%;'>$title</h1>\n\t</div>\n";
        $html = is_null($body) ? $html : $html . "\t<div class='card-body'>\n\t\t$body\n\t</div>\n";
        $html = is_null($footer) ? $html : $html . "\t<div class='card-footer'>\n\t\t$footer\n\t</div>\n";
        $html .= "</div>\n";
        return $html;
    }

    public function lang()
    {
        if (isset($_GET["lang"])) return $_GET["lang"];
        return "en";
    }

    public function text($key)
    {
        if (is_null($this->lang)) {
            $lang_file = __DIR__ . "/../../lang/" . $this->lang() . ".json";
            if (file_exists($lang_file)) $this->lang = json_decode(file_get_contents($lang_file), true);
            else $this->lang = json_decode(file_get_contents(__DIR__ . "/../../lang/en.json"), true);
        }
        if (isset($this->lang[$key])) echo ($this->lang[$key]);
        else echo ("$key undefined");
    }

    public function __destruct()
    {
        $this->close();
    }
}
