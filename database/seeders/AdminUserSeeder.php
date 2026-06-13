<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sportstream.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }
    }
}
