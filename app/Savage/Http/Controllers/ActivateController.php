<?php
namespace Savage\Http\Controllers;

use Savage\Http\Auth\Models\User;

/**
 * ActivateController handles activating a user's account.
 */

class ActivateController extends Controller
{
    public function get()
    {
        $email = $this->param('email');
        $hash = $this->param('identifier');

        $user = User::where('email', $email)->first();

        if($user) {
            if($user->active) {
                $this->flash('success', 'Your account has already been activated!');
                return $this->redirect('auth.login');
            }

            if($this->hash()->verify($this->hash()->hash($hash), $user->active_hash)) {
                $user->update([
                    'active' => true,
                    'active_hash' => null
                ]);


                $this->flash('success', 'Your account has been activated!');
                return $this->redirect('auth.login');
            }
        }

        $this->flash('error', 'That activation link was invalid.');
        return $this->redirect();
    }
}
