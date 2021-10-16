<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model
{
    protected static function getDB()
    {
        try {
            static $db = null;

            if ($db === null) {
                $dsn = 'mysql:host=' . Config::DB_HOST . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $database_sql = "CREATE DATABASE IF NOT EXISTS feedback_system";
//                $s=$db->query($database_sql);
//                var_dump($s);
//                exit;
                if ($db->query($database_sql) === FALSE) {
                    return true;
                }

                $permissions_table = "CREATE TABLE IF NOT EXISTS feedback_system.permissions(
                                          id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                          description VARCHAR(255) NOT NULL)";

                $roles_table = "CREATE TABLE IF NOT EXISTS feedback_system.roles(
                                    id INTEGER UNSIGNED  NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                    name VARCHAR(255) NOT NULL)";

                $roles_permissions = "CREATE TABLE IF NOT EXISTS feedback_system.roles_permissions(
                                          role_id INTEGER UNSIGNED NOT NULL,
                                          permission_id INTEGER UNSIGNED NOT NULL,
                                          FOREIGN KEY (role_id) REFERENCES feedback_system.roles(id) ON DELETE CASCADE,
                                          FOREIGN KEY (permission_id) REFERENCES feedback_system.permissions(id) ON DELETE CASCADE)";

                $users_table = "CREATE TABLE IF NOT EXISTS feedback_system.users(
                                    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                                    name VARCHAR(255) NOT NULL, 
                                    username VARCHAR(255) NOT NULL UNIQUE,  
                                    email VARCHAR(255) NOT NULL UNIQUE, 
                                    password VARCHAR(255) NOT NULL,
                                    role_id INT(11) UNSIGNED NOT NULL DEFAULT 1,
                                    FOREIGN KEY (role_id) REFERENCES feedback_system.roles(id) ON DELETE CASCADE)";

                $messages_table = "CREATE TABLE IF NOT EXISTS feedback_system.messages(
                                       id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                       first_name VARCHAR(255) NOT NULL,
                                       last_name VARCHAR(255) NOT NULL,
                                       email VARCHAR(255) NOT NULL,
                                       message VARCHAR(600) NOT NULL)";
//                if ($db->query($table_sql) === FALSE) {
//                    throw new Exception("Table not created");
////                    return true;
//                }
                $db->exec($permissions_table);
                $db->exec($roles_table);
                $db->exec($roles_permissions);
                $db->exec($users_table);
                $db->exec($messages_table);
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $db;
    }
}
