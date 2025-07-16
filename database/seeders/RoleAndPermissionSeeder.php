<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create employee management permissions (matching their system)
        $tables = [
            'employees',
            'departments',
            'leaves',
            'freelancer_projects',
            'types',
            'payment_types',
            'operators',
            'statuses',
            'reasons',
            'materials',  // Add materials to the tables array
        ];

        foreach ($tables as $table) {
            Permission::create(['name' => "view $table"]);
            Permission::create(['name' => "create $table"]);
            Permission::create(['name' => "edit $table"]);
            Permission::create(['name' => "delete $table"]);
        }

        // Create project management permissions
        $projectPermissions = [
            // Project Management
            'view all projects',
            'view assigned projects',
            'create projects',
            'edit projects',
            'delete projects',
            'assign projects',
            'update project status',
            
            // Task Management
            'view all tasks',
            'view assigned tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'assign tasks',
            'update task status',
            
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Reports
            'view reports',
            'generate reports',
            
            // Settings
            'manage settings',
            
            // Chat/Communication
            'view chat',
            'send messages',
            
            // Daily Reports
            'view daily reports',
            'create daily reports',
            'edit daily reports',
            
            // Notifications
            'view notifications',
            'manage notifications',

            // Material Management
            'manage materials',
            'approve material reservations',
            'view material history',
            'create material reservations',
            'manage material reservations'
        ];

        foreach ($projectPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles for both systems
        $employeeRole = Role::create(['name' => 'employee']);
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super_admin']);
        
        // Project management roles
        $directorRole = Role::create(['name' => 'director']);
        $supervisorRole = Role::create(['name' => 'supervisor']);
        $teamLeaderRole = Role::create(['name' => 'team_leader']);

        // Super Admin gets all permissions (matching their system)
        $superAdminRole->syncPermissions(Permission::all());

        // Project Management System Roles
        $directorPermissions = Permission::all()->pluck('name')->toArray();
        $directorRole->syncPermissions($directorPermissions);

        $supervisorPermissions = [
            // Project Management
            'view all projects', 'view assigned projects',
            'assign projects', 'update project status',
            'view all tasks', 'view assigned tasks',
            'create tasks', 'edit tasks',
            'assign tasks', 'update task status',
            'view users',
            'view reports',
            'view chat', 'send messages',
            'view daily reports',
            'view notifications', 'manage notifications'
        ];
        $supervisorRole->syncPermissions($supervisorPermissions);

        $teamLeaderPermissions = [
            // Project Management
            'view assigned projects',
            'update project status',
            'view assigned tasks',
            'create tasks', 'edit tasks',
            'assign tasks', 'update task status',
            'view users',
            'view reports',
            'view chat', 'send messages',
            'view daily reports', 'create daily reports', 'edit daily reports',
            'view notifications'
        ];
        $teamLeaderRole->syncPermissions($teamLeaderPermissions);

        // Admin Role Permissions
        $adminPermissions = [
            // Material Management
            'view materials',
            'create materials',
            'edit materials',
            'delete materials',
            'manage materials',
            'approve material reservations',
            'view material history',
            'create material reservations',
            'manage material reservations',
            // Add other admin permissions as needed
            'view users',
            'view reports',
            'view notifications',
            'manage notifications'
        ];
        $adminRole->syncPermissions($adminPermissions);

        // Employee Role gets basic material viewing permissions
        $employeePermissions = [
            'view assigned projects',
            'view chat',
            'send messages',
            'view daily reports',
            'view notifications'
        ];
        $employeeRole->syncPermissions($employeePermissions);
    }
} 