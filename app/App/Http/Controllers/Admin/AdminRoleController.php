<?php

namespace App\Http\Controllers\Admin;

use App\Database\Role;
use App\Http\Controllers\Controller;

class AdminRoleController extends Controller
{
    public function get()
    {
        return $this->render('admin/role/list', [
            'roles' => Role::all(),
        ]);
    }

    public function getEdit($roleId)
    {
        $role = Role::where('id', $roleId)->first();

        if(!$this->auth()->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.role.general.cant_edit'));
            return $this->redirect('admin.roles.list');
        }

        return $this->render('admin/role/edit', [
            'role' => $role,
        ]);
    }

    public function postEdit($roleId)
    {
        $title = $this->param('title');

        $role = Role::where('id', $roleId)->first();

        if(!$this->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.role.general.not_authorized'));
            return $this->redirect('admin.roles.list');
        }

        $validator = $this->validator()->validate([
            'title|Title' => [$title, "required|adminUniqueTitle({$title},{$role->id})"],
        ]);

        if(!$validator->passes()) {
            $this->flashNow('error', $this->lang('admin.general.fail'));
            return $this->render('admin/role/edit', [
                'role' => $role,
                'errors' => $validator->errors(),
            ]);
        }

        $role->update([
            'title' => $title
        ]);

        $this->flash('success', $this->lang('admin.general.success'));
        return $this->redirect('admin.roles.edit', [
            'roleId' => $roleId,
        ]);
    }

    public function getCreate()
    {

        if(!$this->auth()->user()->can('create role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.role.general.cant_create'));
            return $this->redirect('admin.roles.list');
        }

        return $this->render('admin/role/create');
    }

    public function postCreate()
    {
        $title = $this->param('title');

        if(!$this->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.role.general.not_authorized'));
            return $this->redirect('admin.roles.list');
        }

        $validator = $this->validator()->validate([
            'title|Title' => [$title, "required|adminUniqueTitle()"],
        ]);

        if(!$validator->passes()) {
            $this->flashNow('error', $this->lang('admin.general.fail'));
            return $this->render('admin/role/create', [
                'errors' => $validator->errors(),
            ]);
        }

        Role::create([
            'title' => $title
        ]);

        $this->flash('success', $this->lang('admin.general.created'));
        return $this->redirect('admin.roles.list');
    }

    public function getDelete($roleId)
    {
        $role = Role::where('id', $roleId)->first();

        if(!$this->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.roles.list');
        }

        return $this->render('admin/role/delete', [
            'role' => $role,
        ]);
    }

    public function postDelete($roleId)
    {
        $role = Role::where('id', $roleId)->first();

        if(!$this->user()->can('delete role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.roles.list');
        }

        $delete = $this->param('delete');

        if(!$delete) {
            return $this->redirect('admin.home');
        }

        if($delete === "true") {
            $role->delete();
            $this->flash('success', $this->lang('admin.general.deleted'));
            return $this->redirect('admin.roles.list');
        }

        $this->flash('info', $this->lang('admin.general.not_deleted'));
        return $this->redirect('admin.roles.edit', [
            'roleId' => $roleId
        ]);
    }
}
