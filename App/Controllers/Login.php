<?php

namespace App\Controllers;

use Core\Model;
use Core\View;
use App\Models\User;
use App\Auth;

class Login extends \Core\Controller
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
            $this->redirect(Auth::getReturnToPage());
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
