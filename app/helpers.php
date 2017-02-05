<?php

if(!function_exists('env')) 
{
    function env($key, $default = null) {
        $value = getenv($key);

        if($value === false) {
            return $default;
        }

        return $value;
    }
}