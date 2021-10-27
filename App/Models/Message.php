<?php

namespace App\Models;

use Core\ErrorHandler;
use Core\Validator;
use PDO;
use Core\Session;
use Core\Model;

class Message extends Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function messagesTable()
    {
        self::createTableIfNotExists('messages', [
            'id INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'first_name VARCHAR(255) NOT NULL',
            'last_name VARCHAR(255) NOT NULL',
            'email VARCHAR(255) NOT NULL',
            'message VARCHAR(600) NOT NULL',
        ]);
    }

    public function validate()
    {
        $errorHandler = new ErrorHandler;
        $validator = new Validator($errorHandler);
        $validator->check($_POST, [
            'first_name' => [
                'required' => true,
            ],
            'last_name' => [
                'required' => true,
            ],
            'email' => [
                'required' => true,
                'email' => true,
            ],
            'message' => [
                'required' => true,
                'maxLength' => 600
            ],
        ]);
    }

    public function save()
    {
        $this->validate();

        if (empty(Session::get('errors'))) {
            $db = static::getDB();

            $sql = 'INSERT INTO feedback_system.messages (first_name, last_name, email, message) VALUES (:first_name, :last_name, :email, :message)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':message', $this->message, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function all()
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM messages';
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = static::getDB();

        $sql = "SELECT * FROM messages WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function delete($id)
    {
        $db = static::getDB();

        $sql = "DELETE FROM messages WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}