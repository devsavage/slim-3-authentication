<?php
namespace App\Http\Controllers\Auth;

use App\Database\User;
use App\Http\Controllers\Controller;
use App\Lib\Session;

class ActivationController extends Controller
{
    public function get()
    {
        $identifier = $this->param('identifier');

        // Make sure we check for an identifier first.
        if(!$identifier) {
            return $this->redirect('home');
        }

        $user = User::where('active_hash', $identifier)->first();

        if(!$user) {
            $this->flash('error', $this->lang('alerts.account.invalid_active_hash'));
            return $this->redirect('auth.login');
        }

        if($user->active) {
            $this->flash("info", $this->lang('alerts.account.already_activated'));
            
            $user->update([
                'active_hash' => null
            ]);

            return $this->redirect('auth.login');
        }

        if(!$user->active) {
            $user->update([
                'active' => true,
                'active_hash' => null,
            ]);

            $this->flash("success", $this->lang('alerts.account.activated'));

            return $this->redirect('auth.login');
        }
    }

    public function getResend()
    {
        if(Session::exists('temp_user_id')) {
            $user = User::where('id', Session::get('temp_user_id'))->first();

            if(!$user) {
                return $this->redirect('home');
            }

            $activeHash = $this->hash->generate(128);

            $user->update([
                'active_hash' => $activeHash
            ]);

            $this->mail->send('/mail/auth/activate.twig', ['hash' => $activeHash, 'user' => $user], function($message) use ($user) {
                $message->to($user->email);
                $message->subject($this->lang('mail.activation.subject'));
            });

            Session::destroy('temp_user_id');

            $this->flash('info', $this->lang('alerts.login.resend_activation'));
            return $this->redirect('auth.login');
        }

        return $this->redirect('auth.login');
    }
}
