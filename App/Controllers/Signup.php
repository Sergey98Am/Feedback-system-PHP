<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;
use Core\Controller;

class Signup extends Controller
{
    public function newAction()
    {
        User::usersTable();
        $this->requireToBeGuest();
        View::renderTemplate('Signup/new.html');
    }

    public function createAction()
    {
        $this->requireToBeGuest();
        $user = new User($_POST);

        if ($user->save()) {
            $this->redirect('/login/new');
        } else {
            $this->redirect('/signup/new');
        }
    }
}
