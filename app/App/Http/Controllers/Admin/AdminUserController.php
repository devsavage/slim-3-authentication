<?php
namespace App\Http\Controllers\Admin;

use App\Database\User;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function get()
    {
        return $this->render('admin/user/list', [
            'users' => User::all()->except($this->user()->id),
        ]);
    }

    public function getEdit($userId)
    {
        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('edit users')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        if($this->param('revoke')) {
            $revoke = $this->param('revoke');
            switch ($revoke) {
                case 'remember':
                    $user->removeRememberCredentials();
                    $this->flash('success', $this->lang('admin.user.revoke.remember'));
                    return $this->redirect('admin.users.edit', [
                        'userId' => $user->id,
                    ]);
                    break;
                case 'recovery':
                    $user->revokeRecoveryHash();
                    $this->flash('success', $this->lang('admin.user.revoke.recovery'));
                    return $this->redirect('admin.users.edit', [
                        'userId' => $user->id,
                    ]);
                    break;
                default:
                    continue;
                    break;
            }
        }

        return $this->render('admin/user/edit', [
            'user' => $user,
        ]);
    }

    public function getEditProfile($userId)
    {
        return $this->getEdit($userId);
    }

    public function postEditProfile($userId)
    {
        $username = $this->param('username');
        $email = $this->param('email');

        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('edit users')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        $validator = $this->validator()->validate([
            'username|Username' => [$username, "required|adminUniqueUsername({$user->username})"],
            'email|E-Mail' => [$email, "required|email|adminUniqueEmail({$user->email})"],
        ]);

        if(!$validator->passes()) {
            $this->flashNow('error', $this->lang('admin.user.update.profile.fail'));
            return $this->render('admin/user/edit', [
                'user' => $user,
                'errors' => $validator->errors(),
            ]);
        }

        $user->update([
            'username' => $username,
            'email' => $email
        ]);

        $this->flash('success', $this->lang('admin.user.update.profile.success'));
        return $this->redirect('admin.users.edit', [
            'userId' => $userId,
        ]);
    }

    public function getEditSettings()
    {
        return $this->getEdit($userId);
    }

    public function postEditSettings($userId)
    {
        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('edit users')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        $active = $this->param('active');

        switch ($active) {
            case 'yes':
                $user->activate();
                $this->flash('success', $this->lang('admin.user.update.settings.active.yes'));
                return $this->redirect('admin.users.edit', [
                    'userId' => $user->id,
                ]);
                break;
            case 'resend':
                $activeHash = $this->hash->generate(128);
                $user->deactivate($activeHash);
                $this->mail->send('/mail/auth/activate.twig', ['hash' => $activeHash, 'user' => $user], function($message) use ($user) {
                    $message->to($user->email);
                    $message->subject($this->lang('mail.activation.subject'));
                });
                $this->flash('success', $this->lang('admin.user.update.settings.active.resend'));
                return $this->redirect('admin.users.edit', [
                    'userId' => $user->id,
                ]);
                break;
            case 'no':
                $user->deactivate();
                $this->flash('success', $this->lang('admin.user.update.settings.active.no'));
                return $this->redirect('admin.users.edit', [
                    'userId' => $user->id,
                ]);
                break;
            default:
                continue;
                break;
        }
    }

    public function getDelete($userId)
    {
        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('edit users')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        return $this->render('admin/user/delete', [
            'user' => $user,
        ]);
    }

    public function postDelete($userId)
    {
        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('delete users')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        $delete = $this->param('delete');

        if(!$delete) {
            return $this->redirect('admin.home');
        }

        if($delete === "true") {
            $user->delete();
            $this->flash('success', $this->lang('admin.user.general.user_deleted'));
            return $this->redirect('admin.users.list');
        }

        $this->flash('info', $this->lang('admin.user.general.user_not_deleted'));
        return $this->redirect('admin.users.edit', [
            'userId' => $userId
        ]);
    }

    public function postUpdateRole($userId, $role, $action)
    {
        $user = User::where('id', $userId)->first();

        if(!$this->authorize($user)) {
            return $this->redirect('admin.users.list');
        }

        if(!$this->user()->can('manage roles') || $role == "admin" && !$this->user()->can('make admin')) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.users.list');
        }

        switch ($action) {
            case 'set':
                $user->giveRole($role);
                $this->flash("raw_success", "You have set the role to <b>{$role}</b> for <b>{$user->username}</b>.");
                break;
            case 'remove':
                $user->removeRole($role);
                $this->flash("raw_success", "You have removed the role <b>{$role}</b> from <b>{$user->username}</b>.");
                break;
            default:
                continue;
                break;
        }

        return $this->redirect('admin.users.edit', [
            'userId' => $userId
        ]);
    }

    protected function authorize($user)
    {
        if(!$user) {
            $this->flash('error', $this->lang('admin.user.general.user_not_found'));
            return false;
        }

        if($user->id === $this->user()->id) {
            $this->flash('error', $this->lang('admin.user.general.user_edit_from_settings'));
            return false;
        }

        if(!$this->user()->canEdit($user)) {
            $this->flash('error', $this->lang('admin.user.general.cant_edit_user'));
            return false;
        }

        return true;
    }
}
