<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['guard_name' => 'web']
        );

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@andradeescobar.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@andradeescobar.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
//                'email_verified_at' => now(),
            ]
        );

        // Assign super-admin role
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole($superAdminRole);
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@andradeescobar.com');
        $this->command->info('Password: password123');
        $this->command->info('Role: super-admin');
    }
}
