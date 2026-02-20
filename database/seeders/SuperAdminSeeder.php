<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Assign super_admin role
        $superAdmin->assignRole('super_admin');

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: password');
    }
}
