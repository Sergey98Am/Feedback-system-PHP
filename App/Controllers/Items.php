<?php

namespace App\Controllers;

use App\Auth;
use Core\View;
use App\Controllers\Authenticated;

class Items extends Authenticated
{
    public function indexAction()
    {
        View::renderTemplate('Items/index.html');
    }

    public function newAction()
    {
        echo 'new action';
    }

    public function showAction()
    {
        echo 'show action';
    }
}
