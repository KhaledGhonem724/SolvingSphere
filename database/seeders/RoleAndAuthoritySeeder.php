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
            ['name' => 'manage_admins', 'description' => 'Add/kick admins, manage roles and authorities.'],
            ['name' => 'manage_tickets', 'description' => 'Assign reports as tasks to other admins.'],
            ['name' => 'manage_problems', 'description' => 'Edit or delete scraped problems.'],
            ['name' => 'manage_blogs', 'description' => 'Hide blogs and comments.'],
            ['name' => 'manage_groups', 'description' => 'Hide groups.'],
            ['name' => 'manage_users', 'description' => 'Ban users.'],
            ['name' => 'basic_admin', 'description' => 'Access statistics, logs, and assigned tasks.'],
        ])->mapWithKeys(fn ($a) => [$a['name'] => Authority::firstOrCreate(['name' => $a['name']], ['description' => $a['description']])]);

        // Roles
        $roles = [
            'Super Admin' => $authorities->keys()->all(), // all
            'Manager' => ['manage_tickets', 'basic_admin'],
            'Coach' => ['manage_problems', 'basic_admin'],
            'Inspector' => ['manage_groups', 'manage_blogs', 'manage_users', 'basic_admin'],
        ];

        foreach ($roles as $roleName => $authorityKeys) {
            $role = Role::firstOrCreate(['name' => $roleName], ['description' => "$roleName role"]);
            $role->authorities()->sync($authorities->only($authorityKeys)->pluck('id'));
        }
    }
}
