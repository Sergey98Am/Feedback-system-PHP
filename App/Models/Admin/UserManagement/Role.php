<?php

namespace App\Models\Admin\UserManagement;

use PDO;
use Core\Session;

class Role extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function validate()
    {
        if ($this->name == '') {
            Session::set('errors', 'name', 'Name is required');
        }
    }

    public function all()
    {
        $db = static::getDB();
        $sql = 'SELECT * FROM feedback_system.roles';
//        $sql = "SELECT * FROM feedback_system.roles
//        JOIN feedback_system.roles_permissions ON feedback_system.roles.id = feedback_system.roles_permissions.role_id
//        JOIN feedback_system.permissions ON feedback_system.roles_permissions.permission_id = feedback_system.permissions.id GROUP BY
//     feedback_system.roles.id HAVING COUNT(feedback_system.permissions.description) > 1";
        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $this->validate();

        if (empty(Session::get('errors'))) {
            $db = static::getDB();

            $sql = 'INSERT INTO feedback_system.roles (name) VALUES (:name)';

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);

            if ($stmt->execute()) {
//                $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
                self::createRolePermissions($db->lastInsertId());
            }

            return true;
        }

        return false;
    }

    public function createRolePermissions($role_id)
    {
        $db = static::getDB();

        $values = [];

        $permission_ids = $this->permissions;
        foreach ($permission_ids as $permission_id) {
            $value = "($role_id, $permission_id)";
            array_push($values, $value);
        }

        $values = implode(",", $values);
        var_dump($values);

        $sql = "INSERT INTO feedback_system.roles_permissions (role_id, permission_id) VALUES $values";

        $stmt = $db->prepare($sql);

        return $stmt->execute();
    }
}
