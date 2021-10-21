<?php

namespace App\Models;

use Core\Model;
use PDO;
use Core\Session;

class Message extends \Core\Model
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

    public function validate()
    {
        if ($this->first_name == '') {
            Session::set('errors', 'first_name','First Name is required');
        }

        if ($this->last_name == '') {
            Session::set('errors', 'last_name','Last Name is required');
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            Session::set('errors', 'email','Invalid email');
        }

        if ($this->message == '') {
            Session::set('errors', 'message','Message is required');
        }
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
}
