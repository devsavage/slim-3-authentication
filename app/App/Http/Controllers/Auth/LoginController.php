<?php
namespace App\Http\Controllers\Auth;

use Carbon\Carbon;

use App\Database\User;
use App\Http\Controllers\Controller;
use App\Lib\Cookie;
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
        $remember = $this->param('remember');

        $validator = $this->validator()->validate([
            'username|Username' => [$username, 'required'],
            'password|Password' => [$password, 'required'],
        ]);

        if($validator->passes()) {
            $user = User::where('username', $username)->orWhere('email', $username)->first();

            if(!$user || !$this->hash->verifyPassword($password, $user->password)) {
                $this->flash("error", $this->lang('alerts.login.invalid'));
                return $this->redirect('auth.login');
            } else if($user && !(bool)$user->active) {
                Session::set('temp_user_id', $user->id);
                $this->flash("raw_warning", "The account you are trying to access has not been activated. <a class='alert-link' href='" . $this->router()->pathFor('auth.activate.resend') . "'>Resend activation link</a>");
                return $this->redirect('auth.login');
            } else if($user && $this->hash->verifyPassword($password, $user->password)) {
                Session::set(env('APP_AUTH_ID', 'user_id'), $user->id);
                
                if(Session::exists('temp_user_id')) {
                    Session::destroy('temp_user_id');
                }

                if($remember === "on") {
                    $rememberIdentifier = $this->hash->generate(128);
                    $rememberToken = $this->hash->generate(128);

                    $user->updateRememberCredentials($rememberIdentifier, $this->hash->hash($rememberToken));

                    Cookie::set(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'), 
                        "{$rememberIdentifier}.{$rememberToken}",
                        Carbon::now()->addWeek(2)->timestamp
                    );
                }
                
                return $this->redirect('home');
            }
        }

        $this->flashNow("error", $this->lang('alerts.login.error'));
        return $this->render('auth/login', [
            'errors' => $validator->errors(),
        ]);
    }
}
