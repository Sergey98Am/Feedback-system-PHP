<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;

class Login extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['username'], $_POST['password']);

        if ($user) {
            $this->redirect('/');
        } else {
            View::renderTemplate('Login/new.html');
        }
    }
}
