<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\Role as RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            RoleEnum::SUPER_ADMIN->value,
            RoleEnum::MANAGER->value,
            RoleEnum::USER->value,
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $user = User::find(1);
        if ($user) {
            $user->assignRole(RoleEnum::SUPER_ADMIN->value);
        }
    }
}
