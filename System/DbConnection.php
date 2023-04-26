<?php

namespace System;
use PDO;
use PDOStatement;

class DbConnection
{
    protected PDO $db;
    public static $instance;

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    protected function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=restapi', 'root', 'toor', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query;
    }
}