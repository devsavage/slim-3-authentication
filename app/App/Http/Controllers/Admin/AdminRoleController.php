<?php

namespace App\Http\Controllers\Admin;

use App\Database\Role;
use App\Database\Permission;
use App\Http\Controllers\Controller;

class AdminRoleController extends Controller
{
    public function get()
    {
        return $this->render('admin/role/list', [
            'roles' => Role::where("hidden", false)->paginate(),
        ]);
    }

    public function getEdit($roleId)
    {
        $role = Role::with('permissions')->where('id', $roleId)->first();

        if((bool)$role->hidden) {
            $this->flash('error', $this->lang('admin.role.general.cant_edit'));
            return $this->redirect('admin.roles.list');
        }

        $permissions = Permission::all();

        if(!$this->auth()->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.role.general.cant_edit'));
            return $this->redirect('admin.roles.list');
        }

        return $this->render('admin/role/edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function postEdit($roleId)
    {
        $title = $this->param('title');
        $permissions = $this->param('permissions');

        $role = Role::where('id', $roleId)->where("hidden", false)->first();

        if(!$this->user()->can('edit role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash("error", $this->lang('admin.role.general.not_authorized'));
            return $this->redirect('admin.roles.list');
        }

        $validator = $this->validator()->validate([
            'title|Title' => [$title, "required|adminUniqueTitle({$title},{$role->id})"],
            'permissions|Permissions' => [$permissions, "array"]
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

        $role->permissions()->detach();

        if($permissions){
            foreach ($permissions as $permission_name) {
                $permission = Permission::where('id',$permission_name)->first();
                $role->givePermissionTo($permission);
            }
        }

        $this->flash('success', $this->lang('admin.general.success'));
        return $this->redirect('admin.roles.edit', [
            'roleId' => $roleId,
        ]);
    }

    public function getCreate()
    {
        $permissions = Permission::all();

        if(!$this->auth()->user()->can('create role') && !$this->auth()->user()->isSuperAdmin()) {
            $this->flash('error', $this->lang('admin.role.general.cant_create'));
            return $this->redirect('admin.roles.list');
        }

        return $this->render('admin/role/create',[
            'permissions' => $permissions,
        ]);
    }

    public function postCreate()
    {
        $title = $this->param('title');
        $permissions = $this->param('permissions');

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

        $role = Role::create([
            'title' => $title
        ]);

        $role->permissions()->detach();
        if($permissions){
            foreach ($permissions as $permission_name) {
                $permission = Permission::where('id',$permission_name)->first();
                $role->givePermissionTo($permission);
            }
        }

        $this->flash('success', $this->lang('admin.general.created'));
        return $this->redirect('admin.roles.list');
    }

    public function getDelete($roleId)
    {
        $role = Role::where('id', $roleId)->where("hidden", false)->first();

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
        $role = Role::where('id', $roleId)->where("hidden", false)->first();

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
