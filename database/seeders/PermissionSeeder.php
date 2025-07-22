<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            PermissionEnum::SUPER_ADMIN->value,
            PermissionEnum::MANAGER->value,
            PermissionEnum::USER->value,
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $rolePermissions = [
            RoleEnum::SUPER_ADMIN->value => [PermissionEnum::SUPER_ADMIN->value],
            RoleEnum::MANAGER->value => [PermissionEnum::MANAGER->value],
            RoleEnum::USER->value => [PermissionEnum::USER->value],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($perms);
            }
        }
    }
}
