<?php

namespace App\Http\Controllers\Admin;

use App\Database\Permission;
use App\Http\Controllers\Controller;

class AdminPermissionController extends Controller
{
    public function get()
    {
        return $this->render('admin/permission/list', [
            'permissions' => Permission::paginate(),
        ]);
    }

    public function getEdit($permissionId)
    {
        $permission = Permission::where('id', $permissionId)->first();

        if(!$this->auth()->user()->can('edit permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.permission.general.cant_edit'));
            return $this->redirect('admin.permissions.list');
        }

        return $this->render('admin/permission/edit', [
            'permission' => $permission,
        ]);
    }

    public function postEdit($permissionId)
    {
        $name = $this->param('name');

        $permission = Permission::where('id', $permissionId)->first();

        if(!$this->user()->can('edit permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.permission.general.not_authorized'));
            return $this->redirect('admin.permissions.list');
        }

        $validator = $this->validator()->validate([
            'name|Name' => [$name, "required|adminUniqueName({$name},{$permission->id})"],
        ]);

        if(!$validator->passes()) {
            $this->flashNow('error', $this->lang('admin.general.fail'));
            return $this->render('admin/permission/edit', [
                'permission' => $permission,
                'errors' => $validator->errors(),
            ]);
        }

        $permission->update([
            'name' => $name
        ]);

        $this->flash('success', $this->lang('admin.general.success'));
        return $this->redirect('admin.permissions.edit', [
            'permissionId' => $permissionId,
        ]);
    }

    public function getCreate()
    {

        if(!$this->auth()->user()->can('create permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.permission.general.cant_create'));
            return $this->redirect('admin.permissions.list');
        }

        return $this->render('admin/permission/create');
    }

    public function postCreate()
    {
        $name = $this->param('name');

        if(!$this->user()->can('edit permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.permission.general.not_authorized'));
            return $this->redirect('admin.permissions.list');
        }

        $validator = $this->validator()->validate([
            'name|Title' => [$name, "required|adminUniqueTitle()"],
        ]);

        if(!$validator->passes()) {
            $this->flashNow('error', $this->lang('admin.general.fail'));
            return $this->render('admin/permission/create', [
                'errors' => $validator->errors(),
            ]);
        }

        Permission::create([
            'name' => $name
        ]);

        $this->flash('success', $this->lang('admin.general.created'));
        return $this->redirect('admin.permissions.list');
    }

    public function getDelete($permissionId)
    {
        $permission = Permission::where('id', $permissionId)->first();

        if(!$this->user()->can('edit permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.permissions.list');
        }

        return $this->render('admin/permission/delete', [
            'permission' => $permission,
        ]);
    }

    public function postDelete($permissionId)
    {
        $permission = Permission::where('id', $permissionId)->first();

        if(!$this->user()->can('delete permission') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.user.general.not_authorized'));
            return $this->redirect('admin.permissions.list');
        }

        $delete = $this->param('delete');

        if(!$delete) {
            return $this->redirect('admin.home');
        }

        if($delete === "true") {
            $permission->delete();
            $this->flash('success', $this->lang('admin.general.deleted'));
            return $this->redirect('admin.permissions.list');
        }

        $this->flash('info', $this->lang('admin.general.not_deleted'));
        return $this->redirect('admin.permissions.edit', [
            'permissionId' => $permissionId
        ]);
    }
}
