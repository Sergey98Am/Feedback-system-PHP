<?php

namespace App\Controllers;

use Core\Model;
use Core\View;
use App\Models\User;
use App\Auth;
use Core\Controller;

class Login extends Controller
{
    public function newAction()
    {
        User::usersTable();
        $this->requireToBeGuest();
        View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        $this->requireToBeGuest();
        $user = User::authenticate($_POST['username'], $_POST['password']);

        if ($user) {
            Auth::login($user);
            $this->redirect('/');
        } else {
            View::renderTemplate('Login/new.html');
        }
    }

    public function destroyAction()
    {
        $this->requireLogin();
        Auth::logout();
        $this->redirect('/');
    }
}
