<?php
namespace App\Http\Controllers\Auth;

use App\Database\User;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function get()
    {
        return $this->render('auth/register');
    }

    public function post()
    {
        $username = $this->param('username');
        $email = $this->param('email');
        $password = $this->param('password');
        $confirm_password = $this->param('confirm_password');

        $validator = $this->validator()->validate([
            'username|Username' => [$username, 'required|min(3)|alnumDash'],
            'email|E-Mail' => [$email, 'required|email'],
            'password|Password' => [$password, 'required|min(6)'],
            'confirm_password|Confirm Password' => [$confirm_password, 'required|matches(password)'],
        ]);

        if($validator->passes()) {
            $activeHash = $this->hash->generate(128);

            $user = User::create([
                'username' => $username,
                'email' => $email,
                'password' => $this->hash->password($password),
                'active_hash' => $activeHash,
                'active' => false,
            ]);

            $this->flash('info', "Your account has been created but you will need to activate it. Please check your e-mail for instructions.");

            $this->mail->send('/mail/auth/activate.twig', ['hash' => $activeHash, 'user' => $user], function($message) use ($user) {
                $message->to($user->email);
                $message->subject($this->config->get('lang.mail.activation.subject'));
            });

            return $this->redirect('auth.login');
        }

        $this->flashNow('error', "Please fix any errors with your registration and try again.");
        return $this->render('auth/register', [
            'errors' => $validator->errors(),
        ]);
    }
}
