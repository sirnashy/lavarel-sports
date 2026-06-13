<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            // Group: users
            ['name' => 'manage-users', 'display_name' => 'Manage Users', 'group' => 'users'],
            
            // Group: content
            ['name' => 'manage-featured', 'display_name' => 'Manage Featured Matches', 'group' => 'content'],
            ['name' => 'manage-pages', 'display_name' => 'Manage CMS Pages', 'group' => 'content'],
            
            // Group: monetization
            ['name' => 'manage-advertisements', 'display_name' => 'Manage Advertisements', 'group' => 'monetization'],
            
            // Group: system
            ['name' => 'manage-settings', 'display_name' => 'Manage Site Settings', 'group' => 'system'],
            ['name' => 'view-logs', 'display_name' => 'View Activity Logs', 'group' => 'system'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $p) {
            $createdPermissions[$p['name']] = Permission::updateOrCreate(
                ['name' => $p['name']],
                ['display_name' => $p['display_name'], 'group' => $p['group']]
            );
        }

        // Define Roles
        $roles = [
            'admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Full system access and configurations.',
                'permissions' => ['manage-users', 'manage-featured', 'manage-pages', 'manage-advertisements', 'manage-settings', 'view-logs']
            ],
            'editor' => [
                'display_name' => 'Content Editor',
                'description' => 'Can manage featured matches and CMS pages.',
                'permissions' => ['manage-featured', 'manage-pages']
            ],
            'advertiser' => [
                'display_name' => 'Monetization Manager',
                'description' => 'Can manage advertisement slots and tracking.',
                'permissions' => ['manage-advertisements']
            ]
        ];

        foreach ($roles as $name => $data) {
            $role = Role::updateOrCreate(
                ['name' => $name],
                ['display_name' => $data['display_name'], 'description' => $data['description']]
            );

            // Sync permissions
            $permissionIds = [];
            foreach ($data['permissions'] as $pName) {
                if (isset($createdPermissions[$pName])) {
                    $permissionIds[] = $createdPermissions[$pName]->id;
                }
            }
            $role->permissions()->sync($permissionIds);
        }
    }
}
