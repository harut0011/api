<?php

namespace System;

class BaseModel
{
    public $db;
    public static $instance;
    protected string $table = '';

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    protected function __construct()
    {
        $this->db = DbConnection::getInstance();
        

    }

    public function all()
    {

        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }
}