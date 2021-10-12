<?php

namespace App\Controllers;

use App\Auth;
use \Core\View;
use App\Models\User;

class Signup extends \Core\Controller
{
    public function newAction()
    {
        $this->requireToBeGuest();
        View::renderTemplate('Signup/new.html');
    }

    public function createAction()
    {
        $this->requireToBeGuest();
        $user = new User($_POST);

        if ($user->save()) {
            $user = User::authenticate($_POST['username'], $_POST['password']);
//            if ($user) {
            Auth::login($user);
            $this->redirect('/');
//            }
        } else {
            View::renderTemplate('Signup/new.html',
                [
                    'user' => $user
                ]);
        }

    }
}
