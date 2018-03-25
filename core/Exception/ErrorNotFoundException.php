<?php

namespace core\Exception;

class ErrorNotFoundException extends Exception 
{
    public function __construct($msg = 'Page Not Found', $code = 404)
    {
        parent::__construct($msg, $code);
    }
}