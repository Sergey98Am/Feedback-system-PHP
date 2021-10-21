<?php

namespace App\Controllers\Admin\UserManagement;

use App\Controllers\Authenticated;
use Core\View;
use App\Models\Admin\UserManagement\Permission as PermissionModel;
use PDO;

class Permission extends Authenticated
{
    public function indexAction()
    {
        $permissions = new PermissionModel();
        View::renderTemplate('Admin/UserManagement/Permissions/index.html', [
            'permissions' => $permissions->all()
        ]);
    }

    public function createAction()
    {
        View::renderTemplate('Admin/UserManagement/Permissions/create.html');
    }

    public function storeAction()
    {
        $permission = new PermissionModel($_POST);

        if ($permission->create()) {
            $this->redirect('/admin/permission/index');
        } else {
            $this->redirect('/admin/permission/create');
        }
    }

    public function editAction()
    {
        $id = $this->route_params['id'];
        $id = intval($id);

        if ($id === null) {
            echo 'Error 404';
        }

        $permission = PermissionModel::find($id);

        if (!$permission) {
            echo 'Error 400';
        }

        View::renderTemplate('Admin/UserManagement/Permissions/edit.html',
            [
                'permission' => $permission
            ]);
    }

    public function updateAction()
    {
        $id = $this->route_params['id'];
        $id = intval($id);

        if ($id === null) {
            echo 'Error 404';
        }

        $permission = new PermissionModel($_POST);
        $permission = $permission->update($id);

//        if (!$permission)
//        {
//            echo 'Error 400';
//        }

        if ($permission) {
            $this->redirect('/admin/permission/index');
        } else {
            $this->redirect("/admin/permission/$id/edit");
        }
    }

    public function deleteAction()
    {
        $id = $this->route_params['id'];
        $id = intval($id);

        if ($id === null) {
            echo 'Error 404';
        }

        $permission = new PermissionModel($_POST);
        $permission = $permission->delete($id);

//        if (!$permission)
//        {
//            echo 'Error 400';
//        }

        if ($permission) {
            $this->redirect('/admin/permission/index');
        } else {
            View::renderTemplate('Admin/UserManagement/Permissions/index.html',
                [
                    'permission' => $permission
                ]);
        }
    }
}
