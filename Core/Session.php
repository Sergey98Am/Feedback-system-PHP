<?php

namespace Core;

class Session
{
    public static function setSession($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function get($name)
    {
        return $_SESSION[$name] ?? false;
    }

    public static function set($name, $associative_key, $message)
    {
        return $_SESSION[$name][$associative_key] = $message;
    }

    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
}
