<?php

namespace Savage\Utils;

class Helper
{
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($givenPassword, $knownPassword)
    {
        return password_verify($givenPassword, $knownPassword);
    }
}
