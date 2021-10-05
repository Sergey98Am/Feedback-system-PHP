<?php

namespace App\Controllers;

//use \Core\View;

class Home extends \Core\Controller
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
        echo 'Home';
    }
}
