<?php
namespace App\Http\Controllers\Auth;

use App\Database\User;
use App\Http\Controllers\Controller;
use App\Lib\Session;

class LoginController extends Controller
{
    public function get()
    {
        return $this->render('auth/login');
    }

    public function post()
    {
        $username = $this->param('username');
        $password = $this->param('password');

        $validator = $this->validator()->validate([
            'username|Username' => [$username, 'required'],
            'password|Password' => [$password, 'required'],
        ]);

        if($validator->passes()) {
            $user = User::where('username', $username)->orWhere('email', $username)->first();

            if(!$user || !$this->hash->verifyPassword($password, $user->password)) {
                $this->flash("error", "You have supplied invalid credentials.");
                return $this->redirect('auth.login');
            } else if($user && !(bool)$user->active) {
                $this->flash("raw_warning", "The account you are trying to access has not been activated. <a class='alert-link' href='#'>Resend activation link</a>");
                return $this->redirect('auth.login');
            } else if($user && $this->hash->verifyPassword($password, $user->password)) {
                Session::set(env('APP_AUTH_ID', 'user_id'), $user->id);
                return $this->redirect('home');
            }
        }

        $this->flashNow("error", "Please enter your credentials to continue.");
        return $this->render('auth/login', [
            'errors' => $validator->errors(),
        ]);
    }
}
