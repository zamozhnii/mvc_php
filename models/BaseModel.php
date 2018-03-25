<?php

namespace models;

use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $validator;

    public function __construct(DBDriver $db, Validator $validator, $table)
    {
        $this->db = $db;
        $this->table = $table;
        $this->validator = $validator;
    }

    public function getAll() 
    {
        $sql = sprintf("SELECT * FROM %s", $this->table);
        return $this->db->select($sql);
    }

    public function getById($id) 
    {
        $sql = sprintf("SELECT * FROM %s WHERE book_id = :id", $this->table);
        return $this->db->select($sql, ['id' => $id], DBDriver::FETCH_ONE);
    }

    public function add(array $params)
    {
        $this->validator->execute($params);
        
        if(!$this->validator->success) {
            throw new ModelException($this->validator->errors);
            $this->validator->errors;
        }

        return $this->db->insert($this->table, $params);
    }
}