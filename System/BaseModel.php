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
    protected array $necessaryFields = [];

    public static function getInstance()
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

    public function edit(int $id, array $fieldsToEdit): bool
    {   
        $errors = $this->checkFields($fieldsToEdit);
        if (!empty($errors)) {
            throw new ExcValidation($errors);
        }
        
        $fieldsStr = '';

        foreach ($fieldsToEdit as $k => $v) {
            $fieldsStr .= "$k = :$k";
        }

        $sql = "UPDATE {$this->table} SET $fieldsStr WHERE {$this->primaryKey} = :id";
        // echo $sql;
        // die;
        $this->db->query($sql, $fieldsToEdit + ['id' => $id]);

        return true;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $this->db->query($sql, ['id' => $id]);
        return true;
    }

    protected function checkFields(array $fields): array
    {
        $errors = [];
        
        if ($fields === null || empty($fields)) {
            $errors[] = 'Empty data';
        }

        foreach ($this->necessaryFields as $necessaryField) {
            if ($fields[$necessaryField]) {
                $errors[] = "Some necessary fields don\'t exist ($necessaryField)";
            }
        }
        
        foreach ($fields as $k => $v) {
            if (trim($v) === '') {
                $errors[] = "Field $k is empty";
            }
        }
        
        return $errors;
    }
}