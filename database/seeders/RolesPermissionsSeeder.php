<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Enums\Can;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'user',
            'manager',
            'guest',
        ];

        foreach ($roles as $role) {
            Role::query()->create(['name' => $role]);
        }

        foreach (Can::cases() as $permission) {
            Permission::query()->create(['name' => $permission]);
        }

        $user = Role::query()->where('name', 'user')->first();
        $user->permissions()->attach(Permission::query()->where('name', 'view-user')->first());
    }
}
