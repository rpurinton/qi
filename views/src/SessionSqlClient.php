<?php

namespace Rpurinton\qi;

class SessionSqlClient
{
    public \mysqli|null|bool $sql = null;
    private string $db;
    function __construct($db)
    {
        $this->db = $db;
        $this->connect();
    }
    private function connect()
    {
        $this->sql = \mysqli_connect("127.0.0.1", $this->db, $this->db, $this->db);
        if (!$this->sql) {
            throw new \Exception('Failed to connect to MySQL: ' . \mysqli_connect_error());
        }
    }
    public function query($query)
    {
        if (!\mysqli_ping($this->sql)) {
            $this->connect();
        }
        $result = \mysqli_query($this->sql, $query);
        if (!$result) {
            throw new \Exception('MySQL query error: ' . \mysqli_error($this->sql));
        }
        return $result;
    }
    public function multi($query)
    {
        if (!\mysqli_ping($this->sql)) {
            $this->connect();
        }
        if (!\mysqli_multi_query($this->sql, $query)) {
            throw new \Exception('MySQL multi query error: ' . \mysqli_error($this->sql));
        }
        $results = array();
        do {
            if ($result = \mysqli_store_result($this->sql)) {
                $results[] = $result;
            }
        } while (\mysqli_next_result($this->sql));
        return $results;
    }
    public function insert($query)
    {
        $result = $this->query($query);
        if (!$result) {
            throw new \Exception('MySQL insert error: ' . \mysqli_error($this->sql));
        }
        return $this->insert_id();
    }
    public function count($result)
    {
        return \mysqli_num_rows($result);
    }
    public function assoc($result)
    {
        return \mysqli_fetch_assoc($result);
    }
    public function escape($text)
    {
        return \mysqli_real_escape_string($this->sql, $text);
    }
    public function single($query)
    {
        return \mysqli_fetch_assoc(mysqli_query($this->sql, $query));
    }
    public function insert_id()
    {
        return \mysqli_insert_id($this->sql);
    }
}
