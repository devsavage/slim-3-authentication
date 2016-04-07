<?php

namespace Savage\Http\Controllers;

use Savage\Http\Auth\Models\User;
use Savage\Utils\Helper;

class AuthController extends Controller
{
    public function getLogin()
    {
        return $this->render('auth/login');
    }

    public function postLogin()
    {
        $auth = $this->auth->attemptLogin($this->param('identifier'), $this->param('password'));

        if(!$auth) {
            $this->flash('error', 'The credentials you entered were invalid.');
            return $this->redirect('auth.login');
        }

        return $this->redirect();
    }

    public function getRegister()
    {
        return $this->render('auth/register');
    }

    public function postRegister()
    {
        $validator = $this->validator();

        $username = $this->param('username');
        $email = $this->param('email');
        $password = $this->param('password');
        $confirm_password = $this->param('confirm_password');

        $validator->validate([
            'username|Username' => [$username, 'required|min(3)|max(25)|alnumDash|uniqueUsername'],
            'email|E-Mail' => [$email, 'required|email|uniqueEmail'],
            'password|Password' => [$password, 'required|min(6)|max(255)'],
            'confirm_password|Confirm Password' => [$confirm_password, 'required|matches(password)'],
        ]);

        if($validator->passes()) {
            $user = User::create([
                'username' => $username,
                'email' => $email,
                'password' => Helper::hashPassword($password),
            ]);

            $this->flash('success', 'You have been registered!');

            $this->auth->attemptLogin($user->email, $this->param('password'));

            return $this->redirect();
        }

        $this->flash('error', 'There was an error while trying to register your account.');
        return $this->redirect('auth.register');
    }


    public function getLogout()
    {
        $this->auth->logout();

        return $this->redirect();
    }
}
