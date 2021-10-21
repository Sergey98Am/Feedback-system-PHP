<?php

namespace App\Controllers;

use App\Auth;
use Core\View;
use Core\Controller;

class Home extends Controller
{
    protected function before()
    {
        //echo "(before) ";
//        return false;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
