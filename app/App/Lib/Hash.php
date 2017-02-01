<?php
namespace App\Lib;

use RandomLib\Factory as RandomLib;

class Hash
{
    protected $_random;

    public function __construct()
    {
        $this->_random = new RandomLib;
    }

    public function password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($givenPassword, $knownPassword)
    {
        return password_verify($givenPassword, $knownPassword);
    }

    public function generate($length = 64, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') 
    {
        return $this->generator()->generateString($length, $characters);
    }

    public function hash($input)
    {
        return hash('sha256', $input);
    }

    public function verifyHash($knownHash, $givenHash)
    {
        return $this->hash_verify($knownHash, $givenHash);
    }

    protected function generator()
    {
        return $this->_random->getMediumStrengthGenerator();
    }

    protected function hash_verify($known_string, $user_string)
    {
        if (func_num_args() !== 2) {
            // handle wrong parameter count as the native implentation
            trigger_error('hash_verify() expects exactly 2 parameters, ' . func_num_args() . ' given', E_USER_WARNING);
            return null;
        }

        if (is_string($known_string) !== true) {
            trigger_error('hash_verify(): Expected known_string to be a string, ' . gettype($known_string) . ' given', E_USER_WARNING);
            return false;
        }

        $known_string_len = strlen($known_string);
        $user_string_type_error = 'hash_verify(): Expected user_string to be a string, ' . gettype($user_string) . ' given'; // prepare wrong type error message now to reduce the impact of string concatenation and the gettype call
        if (is_string($user_string) !== true) {
            trigger_error($user_string_type_error, E_USER_WARNING);
            // prevention of timing attacks might be still possible if we handle $user_string as a string of diffent length (the trigger_error() call increases the execution time a bit)
            $user_string_len = strlen($user_string);
            $user_string_len = $known_string_len + 1;
        } else {
            $user_string_len = $known_string_len + 1;
            $user_string_len = strlen($user_string);
        }

        if ($known_string_len !== $user_string_len) {
            $res = $known_string ^ $known_string; // use $known_string instead of $user_string to handle strings of diffrent length.
            $ret = 1; // set $ret to 1 to make sure false is returned
        } else {
            $res = $known_string ^ $user_string;
            $ret = 0;
        }

        for ($i = strlen($res) - 1; $i >= 0; $i--) {
            $ret |= ord($res[$i]);
        }

        return $ret === 0;
    }
}