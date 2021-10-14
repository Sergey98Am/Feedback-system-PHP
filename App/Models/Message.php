<?php

namespace App\Models;

use PDO;

class Message extends \Core\Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
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

    public function validate()
    {
        if ($this->first_name == '') {
            $this->errors[] = 'First Name is required';
        }

        if ($this->last_name == '') {
            $this->errors[] = 'Last Name is required';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if ($this->message == '') {
            $this->errors[] = 'Message is required';
        }
    }
}
