<?php
namespace Savage\Utils;

use RandomLib\Factory as RandomLib;

/**
 * Hash handles the creation and verification of hashes.
 */
class Hash
{
    protected $_random;

    public function __construct()
    {
        $this->_random = new RandomLib();
    }

    public function hash($string)
    {
        return hash('sha256', $string);
    }

    public function make($length = 64, $alnum = true)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if($alnum) {
            return $this->generateString($length, $characters);
        }

        return $this->generateString($length);
    }

    public function verify($given, $known)
    {
        return $this->hash_verify($known, $given);
    }

    private function generateString($length, $characters = null)
    {
        if($characters) {
            return $this->getMediumGenerator()->generateString($length, $characters);
        }

        return $this->getMediumGenerator()->generateString($length);
    }

    private function getMediumGenerator()
    {
        return $this->random()->getMediumStrengthGenerator();
    }

    protected function random()
    {
        return $this->_random;
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
