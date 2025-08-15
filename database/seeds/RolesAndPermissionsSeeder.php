<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'users.index', 'group' => 'tài khoản']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'users.add', 'group' => 'tài khoản']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'users.delete', 'group' => 'tài khoản']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'users.edit', 'group' => 'tài khoản']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'staff.index', 'group' => 'nhân viên']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'staff.add', 'group' => 'nhân viên']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'staff.delete', 'group' => 'nhân viên']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'staff.edit', 'group' => 'nhân viên']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'notification.index', 'group' => 'thông báo']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Push', 'name' => 'notification.add', 'group' => 'thông báo']);
//        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'staff.delete', 'group' => 'thông báo']);
//        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'staff.edit', 'group' => 'thông báo']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'province.index', 'group' => 'tỉnh/tp']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'province.add', 'group' => 'tỉnh/tp']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'province.delete', 'group' => 'tỉnh/tp']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'province.edit', 'group' => 'tỉnh/tp']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'district.index', 'group' => 'quận/huyện']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'district.add', 'group' => 'quận/huyện']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'district.delete', 'group' => 'quận/huyện']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'district.edit', 'group' => 'quận/huyện']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'ward.index', 'group' => 'phường/xã']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'ward.add', 'group' => 'phường/xã']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'ward.delete', 'group' => 'phường/xã']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'ward.edit', 'group' => 'phường/xã']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'street.index', 'group' => 'tên đường']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'street.add', 'group' => 'tên đường']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'street.delete', 'group' => 'tên đường']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'street.edit', 'group' => 'tên đường']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'convenience.index', 'group' => 'tiện nghi trong nhà']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'convenience.add', 'group' => 'tiện nghi trong nhà']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'convenience.delete', 'group' => 'tiện nghi trong nhà']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'convenience.edit', 'group' => 'tiện nghi trong nhà']);

        Permission::create(['guard_name' => 'backend', 'title' => 'Danh sách', 'name' => 'exterior.index', 'group' => 'tiện nghi xung quanh']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Thêm mới', 'name' => 'exterior.add', 'group' => 'tiện nghi xung quanh']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Xóa', 'name' => 'exterior.delete', 'group' => 'tiện nghi xung quanh']);
        Permission::create(['guard_name' => 'backend', 'title' => 'Sửa', 'name' => 'exterior.edit', 'group' => 'tiện nghi xung quanh']);

        // create roles and assign created permissions
//
//        $role = Role::create(['name' => 'writer']);
//        $role->givePermissionTo('edit articles');
//
//        $role = Role::create(['name' => 'moderator']);
//        $role->givePermissionTo(['publish articles', 'unpublish articles']);
//
//        $role = Role::create(['name' => 'super-admin']);
//        $role->givePermissionTo(Permission::all());
    }
}