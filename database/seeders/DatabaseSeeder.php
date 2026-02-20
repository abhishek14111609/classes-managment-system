<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call RolePermissionSeeder to create roles and permissions
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
        ]);

        $this->command->info('Database seeding completed!');
    }
}
