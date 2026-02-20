<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $schoolAdmin = Role::create(['name' => 'school_admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $student = Role::create(['name' => 'student']);

        // Create Permissions
        $permissions = [
            // School Management
            'manage schools',
            'view schools',
            'create schools',
            'edit schools',
            'delete schools',

            // Plan Management
            'manage plans',
            'view plans',
            'create plans',
            'edit plans',
            'delete plans',

            // Class Management
            'manage classes',
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',

            // Batch Management
            'manage batches',
            'view batches',
            'create batches',
            'edit batches',
            'delete batches',

            // Student Management
            'manage students',
            'view students',
            'create students',
            'edit students',
            'delete students',

            // Teacher Management
            'manage teachers',
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',

            // Attendance Management
            'manage attendance',
            'view attendance',
            'mark attendance',

            // Fee Management
            'manage fees',
            'view fees',
            'create fees',
            'edit fees',
            'delete fees',
            'record payments',

            // Sports Events Management
            'manage events',
            'view events',
            'create events',
            'edit events',
            'delete events',

            // Expense Management
            'manage expenses',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',

            // Reports
            'view reports',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles

        // Super Admin - All permissions
        $superAdmin->givePermissionTo(Permission::all());

        // School Admin - School management permissions
        $schoolAdmin->givePermissionTo([
            'view dashboard',
            'manage classes',
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',
            'manage batches',
            'view batches',
            'create batches',
            'edit batches',
            'delete batches',
            'manage students',
            'view students',
            'create students',
            'edit students',
            'delete students',
            'manage teachers',
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            'manage attendance',
            'view attendance',
            'mark attendance',
            'manage fees',
            'view fees',
            'create fees',
            'edit fees',
            'delete fees',
            'record payments',
            'manage events',
            'view events',
            'create events',
            'edit events',
            'delete events',
            'manage expenses',
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'view reports',
        ]);

        // Teacher - Limited permissions
        $teacher->givePermissionTo([
            'view dashboard',
            'view batches',
            'view students',
            'view attendance',
            'mark attendance',
            'view events',
        ]);

        // Student - View only permissions
        $student->givePermissionTo([
            'view dashboard',
            'view attendance',
            'view fees',
            'view events',
        ]);

        $this->command->info('Roles and Permissions created successfully!');
    }
}
