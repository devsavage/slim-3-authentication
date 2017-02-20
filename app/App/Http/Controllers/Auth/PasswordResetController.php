<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    public function getForgot()
    {
        return $this->render('auth/password/forgot');
    }

    public function postForgot()
    {
        $email = $this->param('email');
        $response = $this->recaptcha->verify($this->param('g-recaptcha-response'));

        if(!$response->isSuccess()) {
            $this->flash('warning', $this->lang('alerts.recaptcha_failed'));
            return $this->redirect('auth.password.forgot');
        }

        $validator = $this->validator()->validate([
            'email|E-Mail' => [$email, 'required|email'],
        ]);

        if($validator->passes()) {
            $user = $this->auth->where('email', $email)->first();

            if($user) {
                $identifier = $this->hash->generate();

                $user->update([
                    'recover_hash' => $this->hash->hash($identifier),
                ]);

                $this->mail->send('/mail/password/forgot.twig', ['identifier' => $identifier, 'date' => \Carbon\Carbon::now()->toFormattedDateString(), 'user' => $user], function($message) use ($user) {
                    $message->to($user->email);
                    $message->subject($this->lang('mail.password.forgot.subject'));
                });
            }

            $this->flash("info", $this->lang('alerts.forgot_password_success'));
            return $this->redirect('auth.login');
        }

        $this->flash('error', $this->lang('alerts.forgot_password_failed'));
        return $this->redirect('auth.password.forgot');
    }

    public function getReset()
    {
        $identifier = $this->param('identifier');

        if(!$identifier) {
            return $this->redirect('auth.login');
        }

        return $this->render('auth/password/reset', [
            'identifier' => $identifier,
        ]);
    }

    public function postReset()
    {
        $email = $this->param('email');
        $identifier = $this->param('identifier');
        $newPassword = $this->param('new_password');
        $confirmNewPassword = $this->param('confirm_new_password');

        if(!$email) {
            $this->flash('error', $this->lang('alerts.reset_password_no_email'));
            return $this->redirect('auth.password.reset', [], [
                'identifier' => $identifier,
            ]);
        }

        $user = $this->auth->where('email', $email)->first();

        if(!$user) {
            return $this->redirect('auth.login');
        }

        $knownIdentifier = $user->recover_hash;
        $userHash = $this->hash->hash($identifier);

        if(!$knownIdentifier || !$this->hash->verifyHash($knownIdentifier, $userHash)) {
            $user->update([
                'recover_hash' => null,
            ]);

            $this->flash('error', $this->lang('alerts.reset_password_invalid'));
            return $this->redirect('auth.password.forgot');
        }

        $validator = $this->validator()->validate([
            'new_password|New Password' => [$newPassword, 'required|min(6)'],
            'confirm_new_password|Confirm New Password' => [$confirmNewPassword, 'required|matches(new_password)']
        ]);

        if($validator->passes()) {
            $user->update([
                'recover_hash' => null,
                'password' => $this->hash->password($newPassword),
            ]);

            $this->flash('success', $this->lang('alerts.reset_password_success'));
            return $this->redirect('auth.login');
        }

        $this->flashNow('error', $this->lang('alerts.reset_password_failed'));
        return $this->render('auth/password/reset', [
            'identifier' => $identifier,
            'errors' => $validator->errors(),
        ]);
    }
}
