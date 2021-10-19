<?php

namespace App\Controllers\Admin\UserManagement;

use App\Controllers\Authenticated;
use Core\View;
use App\Models\Admin\UserManagement\Role as RoleModel;
use App\Models\Admin\UserManagement\Permission as PermissionModel;
use PDO;

class Role extends Authenticated
{
    public function indexAction()
    {
        $roles = new RoleModel();
        View::renderTemplate('Admin/UserManagement/Roles/index.html', [
            'roles' => $roles->all(),
        ]);
    }

    public function createAction()
    {
        $permissions = new PermissionModel();
        View::renderTemplate('Admin/UserManagement/Roles/create.html', [
            'permissions' => $permissions->all(),
        ]);
    }

    public function storeAction()
    {
        $role = new RoleModel($_POST);

        if ($role->create()) {
            $this->redirect('/admin/role/index');
        } else {
            $this->redirect('/admin/role/create');
        }
    }
}
