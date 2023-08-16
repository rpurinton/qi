<?php

namespace Rpurinton\qi;

require_once(__DIR__ . '/vendor/autoload.php');

class Utilities
{
    private $tik_token;
    private $parse_down;

    function __construct()
    {
        $this->tik_token = new \TikToken\Encoder();
        $this->parse_down = new \Parsedown();
    }

    public function token_count($text)
    {
        return count($this->tik_token->encode($text));
    }

    public function markdown_to_html($text)
    {
        return $this->parse_down->text($text);
    }
}
