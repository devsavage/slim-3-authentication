<?php
namespace App\Lib;

class Session
{
    public static function get($key, $default = null)
    {
        if(self::exists($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public static function exists($key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    public static function destroy($key)
    {
        if(self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
}