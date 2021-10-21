<?php

namespace App\Models;

use PDO;
use Core\Session;
use Core\Model;

class User extends Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function usersTable()
    {
        Model::createTableIfNotExists('users', [
            'id INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name VARCHAR(255) NOT NULL',
            'username VARCHAR(255) NOT NULL UNIQUE',
            'email VARCHAR(255) NOT NULL UNIQUE',
            'password VARCHAR(255) NOT NULL',
        ]);
    }

    public function validate()
    {
        if ($this->name == '') {
            Session::set('errors', 'name','Name is required');
        }

        if ($this->username == '') {
            Session::set('errors', 'username','Username is required');
        }

        if ($this->usernameExists($this->username)) {
            Session::set('errors', 'username', 'Username already taken');
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            Session::set('errors', 'email', 'Invalid email');
        }

        if ($this->emailExists($this->email)) {
            Session::set('errors', 'email', 'Email already taken');
        }

        if (strlen($this->password) < 6) {
            Session::set('errors', 'password','Please enter at least 6 characters for the password');
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) === 0) {
            Session::set('errors', 'password','Password needs at least one letter');
        }

        if (preg_match('/.*\d+.*/i', $this->password) === 0) {
            Session::set('errors', 'password','Password needs at least on number');
        }

        if ($this->password != $this->confirm_password) {
            Session::set('errors', 'password', 'Password must match confirmation');
        }
    }

    public function save()
    {
        Model::createTableIfNotExists('users', [
            'id INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name VARCHAR(255) NOT NULL',
            'username VARCHAR(255) NOT NULL UNIQUE',
            'email VARCHAR(255) NOT NULL UNIQUE',
            'password VARCHAR(255) NOT NULL',
        ]);
        $this->validate();

        if (empty(Session::get('errors'))) {
            $db = Model::getDB();

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)";

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    protected function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    public static function findByUsername($username)
    {
        $db = Model::getDB();

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    protected function emailExists($email)
    {
        $db = Model::getDB();

        $sql = "SELECT * FROM users WHERE email = :email";
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
        $db = Model::getDB();
        $sql = "SELECT * FROM users WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
}
