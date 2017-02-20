<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function get()
    {
        return $this->render('auth/account/settings');
    }

    public function getProfile()
    {
        return $this->redirect('auth.settings');
    }


    public function getPassword()
    {
        return $this->redirect('auth.settings');
    }

    public function postProfile()
    {
        $email = $this->param('email');

        $validator = $this->validator()->validate([
            'email|E-Mail' => [$email, 'required|email|uniqueEmail'],
        ]);

        if($validator->passes()) {
            $user = $this->user()->update([
                'email' => $email,
            ]);

            $this->flash('success', $this->lang('alerts.account.profile.updated'));
            return $this->redirect('auth.settings');
        }

        $this->flashNow("error", $this->lang('alerts.account.profile.failed'));
        return $this->render('auth/account/settings', [
            'errors' => $validator->errors(),
        ]);
    }

    public function postPassword()
    {
        $currentPassword = $this->param('current_password');
        $newPassword = $this->param('new_password');   
        $confirmNewPassword = $this->param('confirm_new_password');

        $validator = $this->validator()->validate([
            'current_password|Current Password' => [$currentPassword, 'required|matchesCurrentPassword'],
            'new_password|New Password' => [$newPassword, 'required|min(6)'],
            'confirm_new_password|Confirm New Password' => [$confirmNewPassword, 'required|matches(new_password)'],
        ]);

        if($validator->passes()) {
            $this->user()->update([
                'password' => $this->hash->password($newPassword),
            ]);

            $this->flash('success', $this->lang('alerts.account.password.updated'));
            return $this->redirect('auth.settings');
        }

        $this->flashNow("error", $this->lang('alerts.account.password.failed'));
        return $this->render('auth/account/settings', [
            'errors' => $validator->errors(),
        ]);
    }
}
