<?php

namespace System\Exceptions;

use Exception;

class ExcValidation extends Exception
{
    protected array $errors = [];
    public function __construct($errors)
    {
        parent::__construct();
        $this->errors = $errors;
    }
    public function getErrorsList(): array
    {
        return $this->errors;
    }
}