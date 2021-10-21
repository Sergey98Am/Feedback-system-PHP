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
        $this->requireLogin();
        Message::messagesTable();
        $messages = new Message();
        View::renderTemplate('Feedback/list.html', [
            'messages' => $messages->all()
        ]);
    }

    public function show()
    {
        $this->requireLogin();
        $id = $this->route_params['id'];
        $id = intval($id);

        if ($id === null) {
            echo 'Error 404';
        }

        $message = Message::find($id);

        if (!$message) {
            echo 'Error 400';
        }

        View::renderTemplate('Feedback/show.html',
            [
                'message' => $message
            ]);
    }
}