<?php

namespace Savage\Http\Controllers;

class AuthController extends Controller
{
    public function getLogin()
    {
        return $this->render('auth/login');
    }

    public function postLogin()
    {
        //TODO: Setup post login
        return "POST LOGIN";
    }

    public function getRegister()
    {
        return $this->render('auth/register');
    }

    public function postRegister()
    {
        //TODO: Setup post registration
        return "POST REGISTER";
    }
}
