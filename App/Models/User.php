<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
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

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO feedback_system.users (name, username, email, password) VALUES (:name, :username, :email, :password)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate()
    {
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        if ($this->username == '') {
            $this->errors[] = 'Username is required';
        }

        if ($this->usernameExists($this->username)) {
            $this->errors[] = 'Username already taken';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if ($this->emailExists($this->email)) {
            $this->errors[] = 'Email already taken';
        }

        if (strlen($this->password) < 6) {
            $this->errors[] = 'Please enter at least 6 characters for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) === 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) === 0) {
            $this->errors[] = 'Password needs at least on number';
        }

        if ($this->password != $this->confirm_password) {
            $this->errors[] = 'Password must match confirmation';
        }
    }

    protected function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    public static function findByUsername($username)
    {
        $db = static::getDB();

        $sql = 'SELECT * FROM feedback_system.users WHERE username = :username';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    protected function emailExists($email)
    {
        $db = static::getDB();

        $sql = 'SELECT * FROM feedback_system.users WHERE email = :email';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    public static function findById($id)
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM feedback_system.users WHERE id = :id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
}