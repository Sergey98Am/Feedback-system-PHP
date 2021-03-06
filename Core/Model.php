<?php

namespace Core;

use PDO;
use App\Config;

class Model
{
    protected static $db_name = Config::DB_NAME;

    protected $table;

    protected $stmt;

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

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function exists($data)
    {
        $field = array_keys($data)[0];
        return $this->where($field, '=', $data[$field])->count() ? true : false;
    }

    public function where($field, $operator, $value)
    {
        $db = self::getDB();
        $sql = "SELECT * FROM $this->table WHERE $field $operator ?";

        $this->stmt = $db->prepare($sql);

        $this->stmt->execute([$value]);

        return $this;
    }

    public function count()
    {
        return $this->stmt->rowCount();
    }
}
