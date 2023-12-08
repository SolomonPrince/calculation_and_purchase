<?php

namespace App\Exception;

use Exception;

class ValidateException extends Exception
{

    private array $errors = [];

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
