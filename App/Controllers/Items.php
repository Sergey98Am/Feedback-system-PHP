<?php

namespace App\Controllers;

use App\Auth;
use Core\View;

class Items extends \Core\Controller
{
    public function indexAction()
    {
        if (!Auth::isLoggedIn()) {
            $this->redirect('/login/new');
        }

        View::renderTemplate('Items/index.html');
    }
}
