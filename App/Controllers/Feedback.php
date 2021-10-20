<?php

namespace App\Controllers;

use App\Models\Message;
use Core\View;

class Feedback extends \Core\Controller
{
    public function createAction()
    {
        Message::messagesTable();
        View::renderTemplate('Feedback/index.html');
    }

    public function storeAction()
    {
        $message = new Message($_POST);

        if ($message->save()) {
            $this->redirect('/feedback/thank-you');
        } else {
            $this->redirect('/feedback/create');
        }
    }

    public function thankYouAction()
    {
        View::renderTemplate('Feedback/thank-you.html');
    }

    public function list()
    {
        $messages = new Message();
        Message::messagesTable();
        View::renderTemplate('Feedback/list.html', [
            'messages' => $messages->all()
        ]);
    }
}
