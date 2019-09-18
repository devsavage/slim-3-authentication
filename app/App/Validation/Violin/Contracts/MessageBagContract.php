<?php

namespace App\Validation\Violin\Contracts;

interface MessageBagContract
{
    public function has($key);
    public function first($key);
    public function get($key);
    public function all();
    public function keys();
}
