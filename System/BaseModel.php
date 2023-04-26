<?php

namespace System;
use System\Exceptions\Exc404;
use System\Exceptions\ExcValidation;

abstract class BaseModel
{
    public DbConnection $db;
    public static $instance;
    protected string $table = '';
    protected string $primaryKey = '';

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

    public function one(int $id): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey}=:id";
        $todo = $this->db->query($sql, ['id' => $id])->fetch();

        if (!$todo) {
            throw new Exc404("No article found with ID $id");
        }

        return $todo;
    }

    public function add(array $fieldsToAdd): int
    {   
        $errors = $this->checkFields($fieldsToAdd);
        if (!empty($errors)) {
            throw new ExcValidation($errors);
        }
        
        $names = [];
        $masks = [];

        foreach ($fieldsToAdd as $k => $v) {
            $names[] = $k;
            $masks[] = ":$k";
        }

        $namesStr = implode(', ', $names);
        $masksStr = implode(', ', $masks);

        $sql = "INSERT INTO {$this->table} ($namesStr) VALUES ($masksStr)";
        $this->db->query($sql, $fieldsToAdd);

        return $this->db->lastInsertId();
    }

    protected function checkFields(array $fields): array
    {
        $errors = [];
        foreach ($fields as $k => $v) {
            if (trim($v) === '') {
                $errors[] = "Field $k is empty";
            }
            if (mb_strlen(trim($v)) <= 2) {
                $errors[] = "Field $k has not enough symbols. Minimum 2";
            }
        }

        return $errors;
    }
}