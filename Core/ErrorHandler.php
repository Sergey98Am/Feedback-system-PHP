<?php

namespace Core;

use Core\Session;

class ErrorHandler
{
    protected $errors;

    public function __construct()
    {
        $this->errors = $_SESSION['errors'];
    }

    public function addError($error, $key = null)
    {
        Session::set('errors', $key, $error);
    }
}
