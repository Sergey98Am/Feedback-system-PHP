<?php

namespace App\Controllers;

use App\Models\Message;
use Core\View;

class Feedback extends \Core\Controller
{
    public function createAction()
    {
        View::renderTemplate('Feedback/index.html');
    }

    public function storeAction()
    {
        $message = new Message($_POST);

        if ($message->save()) {
            $this->redirect('/feedback/thank-you');
        } else {
            View::renderTemplate('Feedback/index.html',
                [
                    'message' => $message
                ]);
        }
    }

    public function thankYouAction()
    {
        View::renderTemplate('Feedback/thank-you.html');
    }
}
