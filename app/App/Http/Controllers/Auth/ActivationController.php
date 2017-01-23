<?php
namespace App\Http\Controllers\Auth;

use App\Database\User;
use App\Http\Controllers\Controller;

class ActivationController extends Controller
{
    public function get()
    {
        $identifier = $this->param('identifier');

        $user = User::where('active_hash', $identifier)->first();

        if(!$user) {
            // We don't really need to flash a message here since there is not a valid account to activate.
            return $this->redirect('home');
        }

        if($user->active) {
            $this->flash("info", "Your account has already been activated.");
            
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

            $this->flash("success", "Your account has been activated! You can now login.");

            return $this->redirect('auth.login');
        }
    }
}
