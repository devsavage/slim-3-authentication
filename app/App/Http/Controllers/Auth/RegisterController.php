<?php
namespace App\Http\Controllers\Auth;

use App\Database\User;
use App\Http\Controllers\Controller;
use App\Lib\Session;

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

        if($this->config('app.activation.method') === 'recaptcha') {
            $recaptcha = $this->param('g-recaptcha-response');

            if(!$this->recaptcha->verify($recaptcha)->isSuccess()) {
                $this->flash('warning', $this->lang('alerts.recaptcha_failed'));
                return $this->redirect('auth.register');
            }
        }

        $validator = $this->validator()->validate([
            'username|Username' => [$username, 'required|min(3)|alnumDash|uniqueUsername'],
            'email|E-Mail' => [$email, 'required|email|uniqueEmail'],
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

            if($this->config('app.activation.method') === 'mail') {
                $this->flash('info', $this->lang('alerts.registration.requires_mail_activation'));
                $this->mail->send('/mail/auth/activate.twig', ['hash' => $activeHash, 'user' => $user], function($message) use ($user) {
                    $message->to($user->email);
                    $message->subject($this->lang('mail.activation.subject'));
                });
            } else {
                $user->update([
                    'active' => true,
                    'active_hash' => null,
                ]);

                $this->flash('success', $this->lang('alerts.registration.successful'));
            }

            return $this->redirect('auth.login');
        }

        $this->flashNow('error', $this->lang('alerts.registration.error'));
        return $this->render('auth/register', [
            'errors' => $validator->errors(),
        ]);
    }
}
