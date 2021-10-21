<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model
{
    protected static $db_name = Config::DB_NAME;

    public static function getDB()
    {
        try {
            static $db = null;

            if ($db === null) {
                $dsn = 'mysql:host=' . Config::DB_HOST . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $db;
    }

    public static function createDBIfNotExists()
    {
        $db = self::getDB();
        $dbname = "`" . str_replace("`", "``", self::$db_name) . "`";
        $db->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $db->query("use $dbname");
    }

    public static function createTableIfNotExists($table_name, $columns)
    {
        $db = self::getDB();
        $columns = implode(",", $columns);
        $table = "CREATE TABLE IF NOT EXISTS $table_name($columns)";
        $db->exec($table);
    }

//id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//name VARCHAR(255) NOT NULL,
//username VARCHAR(255) NOT NULL UNIQUE,
//email VARCHAR(255) NOT NULL UNIQUE,
//password VARCHAR(255) NOT NULL)

//    protected static function getDB()
//    {
//        try {
//            static $db = null;
//
//            if ($db === null) {
//                $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
//                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
//                // Throw an Exception when an error occurs
//                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
////                $s=$db->query($database_sql);
////                var_dump($s);
////                exit;
//
//                $users_table = "CREATE TABLE IF NOT EXISTS users(
//                                    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//                                    name VARCHAR(255) NOT NULL,
//                                    username VARCHAR(255) NOT NULL UNIQUE,
//                                    email VARCHAR(255) NOT NULL UNIQUE,
//                                    password VARCHAR(255) NOT NULL)";
////
////                $messages_table = "CREATE TABLE IF NOT EXISTS messages(
////                                       id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
////                                       first_name VARCHAR(255) NOT NULL,
////                                       last_name VARCHAR(255) NOT NULL,
////                                       email VARCHAR(255) NOT NULL,
////                                       message VARCHAR(600) NOT NULL)";
////                if ($db->query($table_sql) === FALSE) {
////                    throw new Exception("Table not created");
//////                    return true;
//////                }
////                $db->exec($users_table);
////                $db->exec($messages_table);
//            }
//        } catch (PDOException $e) {
//            echo "Connection failed: " . $e->getMessage();
//        }
//
//        return $db;
//    }
}
