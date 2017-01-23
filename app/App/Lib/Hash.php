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

    protected function generator()
    {
        return $this->_random->getMediumStrengthGenerator();
    }
}