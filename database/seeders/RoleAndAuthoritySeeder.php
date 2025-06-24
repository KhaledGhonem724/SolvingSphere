<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Authority;

class RoleAndAuthoritySeeder extends Seeder
{
    public function run(): void
    {
        // Authorities
        $authorities = collect([
             ['name' => 'manage_admins',
             'description' => 'Add/kick admins, and change their roles.'],
             ['name' => 'manage_roles',
             'description' => 'Manage roles and authorities.'],
             ['name' => 'manage_announcements',
             'description' => 'create/edit announcements'],
             ['name' => 'manage_tasks',
             'description' => 'convert Reports to tasks ,Assign tasks to other admins.'],
             ['name' => 'manage_statistics',
             'description' => 'Access all statistics and website activity log'],
            
             ['name' => 'manage_problems',
             'description' => 'Edit or delete scraped problems.'],
             ['name' => 'manage_blogs',
             'description' => 'Hide blogs and comments.'],
             ['name' => 'manage_groups',
             'description' => 'Hide groups.'],
             ['name' => 'manage_containers',
             'description' => 'Hide containers.'],
             ['name' => 'manage_users',
             'description' => 'Ban users.'],
            
             ['name' => 'basic_admin',
             'description' => 'Access assigned tasks and announcements page.'],
        ])->mapWithKeys(fn ($a) => [$a['name'] => Authority::firstOrCreate(['name' => $a['name']], ['description' => $a['description']])]);

        // Roles
        $roles = [
            'Super Admin' => $authorities->keys()->all(), // all
            'Manager' => ['manage_tasks','manage_announcements', 'basic_admin'],
            'Coach' => ['manage_problems', 'manage_containers', 'manage_users', 'basic_admin'],
            'Data Analyst' => ['manage_statistics', 'basic_admin'],
            'Inspector' => ['manage_groups', 'manage_blogs', 'manage_users', 'basic_admin'],
        ];

        foreach ($roles as $roleName => $authorityKeys) {
            $role = Role::firstOrCreate(['name' => $roleName], ['description' => "$roleName role"]);
            $role->authorities()->sync($authorities->only($authorityKeys)->pluck('id'));
        }
    }
}
