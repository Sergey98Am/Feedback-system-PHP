<?php

namespace App\Models\Admin\UserManagement;

use PDO;
use Core\Session;

class Permission extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function validate()
    {
        if ($this->description == '') {
            Session::set('errors', 'description', 'Description is required');
        }
    }

    public function all()
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM feedback_system.permissions';
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $this->validate();

        if (empty(Session::get('errors'))) {
            $db = static::getDB();

            $sql = 'INSERT INTO feedback_system.permissions (description) VALUES (:description)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public static function find($id)
    {
        $db = static::getDB();

        $sql = "SELECT * FROM feedback_system.permissions WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function update($id)
    {
        $this->validate();

        if (empty(Session::get('errors'))) {
            $db = static::getDB();

            $sql = "UPDATE feedback_system.permissions SET description = :description WHERE id = :id";

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function delete($id)
    {
        $db = static::getDB();

        $sql = "DELETE FROM feedback_system.permissions WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
