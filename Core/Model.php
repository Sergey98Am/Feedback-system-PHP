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

                $table_sql = "CREATE TABLE IF NOT EXISTS feedback_system.users(
                                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                                name VARCHAR(255) NOT NULL, 
                                username VARCHAR(255) NOT NULL UNIQUE,  
                                email VARCHAR(255) NOT NULL UNIQUE, 
                                password VARCHAR(255) NOT NULL)";
                $table_sql = "CREATE TABLE IF NOT EXISTS feedback_system.messages(
                                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                                first_name VARCHAR(255) NOT NULL, 
                                last_name VARCHAR(255) NOT NULL,  
                                email VARCHAR(255) NOT NULL, 
                                message VARCHAR(600) NOT NULL
                                )";
                if ($db->query($table_sql) === FALSE) {
                    throw new Exception("Table not created");
//                    return true;
                }
            }
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $db;
    }
}
