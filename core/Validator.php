<?php

namespace core;

use core\Exception\ValidatorException;

class Validator
{
    public $clean = [];
    public $errors = [];
    public $success = false;
    private $rules;

    public function execute(array $fields)
    {
        if(!$this->rules) {
            throw new ValidatorException('Rules for validation not found');
        }

        foreach($this->rules as $name => $rules) {
            //проверка на обязательное поле
            if (!isset($fields[$name]) && isset($rules['require'])) {
                $this->errors[$name][] = sprintf('Field %s is require!', $name);
            }
            // нет обязательного поля
            if ( !isset($fields[$name]) && (!isset($rules['require']) || !$rules['require']) ) {
                continue;
            }

             if(isset($rules['not_blunk']) && $this->isBlank($fields[$name])) {
                 $this->errors[$name][] = sprintf('Field "%s" is not be blank', $name);
             }

            if (isset($rules['type']) && !$this->isTypeMatching($fields[$name], $rules['type'])) {
                 $this->errors[$name][] = sprintf('Field "%s" must be a %s type', $name, $rules['type']);
             }

             if(isset($rules['length']) && !$this->isLengthMatch($fields[$name], $rules['length'])) {
                 $this->errors[$name][] = sprintf('Field "%s" has an incorrect lenth', $name, $rules['type']);
             }

            if(empty($this->errors[$name])) {
                if(isset($rules['type']) && $rules['type'] === 'string') {
                    $this->clean[$name] = htmlspecialchars(trim($fields[$name]));
                } elseif (isset($rules['type']) && $rules['type'] === 'integer') {
                    $this->clean[$name] = (int)$fields[$name];
                } else {
                    $this->clean[$name] = $fields[$name];
                }
            }
        }
        if(empty($this->errors)) {
            $this->success = true;
        }
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    public function isLengthMatch($field, $length)
    {
        if($isArray = is_array($length)) {
            $max = isset($length[1]) ? $length[1] : false;
            $min = isset($length[0]) ? $length[0] : false;
        } else {
            $max = $length;
            $min = false;
        }

        if($isArray && (!$max || !$min)) {
            throw new ValidatorException('Incorrect data given to method isLengthMatch');
        }

        if(!$isArray && !$max) {
            throw new ValidatorException('Incorrect data given to method isLengthMatch');
        }

        $maxIsMatch = $max ? $this->isLengthMaxMatch($field, $max) : false;
        $minIsMatch = $min ? $this->isLengthMinMatch($field, $min) : false;

        // [
        //     'status' => true (false),
        //     'errors' => [
        //         'expected' => 50,
        //         'given' => 150 ...
        //     ]
        // ]

        return $isArray ? $maxIsMatch && $minIsMatch : $maxIsMatch;
    }

    public function isLengthMaxMatch($field, $length) 
    {
        return strlen($field) > $length === false;
    }

    public function isLengthMinMatch($field, $length)
    {
        return strlen($field) < $length === false;
    }

    public function isTypeMatching($field, $type)
    {
        switch($type) {
            case 'string':
                return is_string($field);
                break;
            case 'int':
            case 'integer':
                return gettype($field) === 'integer' || ctype_digit($field);
                break;
            default:
                throw new ValidatorException('Incorrect type given to method isTypeMatching');
                break;
        }
    }

    public function isBlank($field)
    {
        $field = trim($field);

        return $field === null || $field === '';
    }
}