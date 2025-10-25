<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Log;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find or create the 'admin' role
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator with full access']
        );

        // 2. Check if any user already has the admin role
        $adminUserExists = UserRole::where('role_id', $adminRole->id)->exists();

        // If no admin user exists, assign the role to user with ID 1
        if (!$adminUserExists) {
            $user = User::find(1);

            if ($user) {
                // 3. Assign the admin role to the user
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $adminRole->id,
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'DELETE' => true,
                    'PATCH' => true,
                ]);

                Log::info('Admin role assigned to user with ID 1.');

            } else {
                Log::warning('User with ID 1 not found. Could not assign admin role.');
            }
        } else {
            Log::info('An admin user already exists. No new role assigned.');
        }
    }
}
