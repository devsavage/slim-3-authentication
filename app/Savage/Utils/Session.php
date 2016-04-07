<?php

namespace Savage\Utils;

/**
 * Session contains functions to better use PHP's $_SESSION.
 */
class Session
{
    public static function get($key)
    {
        if(self::exists($key)) {
            return $_SESSION[$key];
        }
    }

    public static function set($key, $value)
    {
        return $_SESSION[$key] = $value;
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
}
