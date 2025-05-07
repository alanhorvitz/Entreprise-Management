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
            'manage notifications'
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

        // Assign permissions to roles
        // Employee Management System Roles
        $employeePermissions = [
            'view employees',
            'view departments',
            'view leaves',
            'view freelancer_projects',
            'view types',
            'view payment_types',
            'view operators',
            'view statuses',
            'view reasons'
        ];
        $employeeRole->syncPermissions($employeePermissions);

        $adminPermissions = [
            // Employee Management - Full access
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view leaves', 'create leaves', 'edit leaves', 'delete leaves',
            'view freelancer_projects', 'create freelancer_projects', 'edit freelancer_projects', 'delete freelancer_projects',
            'view types', 'create types', 'edit types', 'delete types',
            'view payment_types', 'create payment_types', 'edit payment_types', 'delete payment_types',
            'view operators', 'create operators', 'edit operators', 'delete operators',
            'view statuses', 'create statuses', 'edit statuses', 'delete statuses',
            'view reasons', 'create reasons', 'edit reasons', 'delete reasons'
        ];
        $adminRole->syncPermissions($adminPermissions);

        // Super Admin gets all permissions (matching their system)
        $superAdminRole->syncPermissions(Permission::all());

        // Project Management System Roles
        $directorPermissions = Permission::all()->pluck('name')->toArray();
        $directorRole->syncPermissions($directorPermissions);

        $supervisorPermissions = [
            // Employee Management
            'view employees', 'view departments', 'view leaves',
            'view freelancer_projects', 'view types', 'view payment_types',
            'view operators', 'view statuses', 'view reasons',
            
            // Project Management
            'view all projects', 'view assigned projects',
            'create projects', 'edit projects',
            'assign projects', 'update project status',
            'view all tasks', 'view assigned tasks',
            'create tasks', 'edit tasks',
            'assign tasks', 'update task status',
            'view users',
            'view reports', 'generate reports',
            'view chat', 'send messages',
            'view daily reports', 'create daily reports', 'edit daily reports',
            'view notifications', 'manage notifications'
        ];
        $supervisorRole->syncPermissions($supervisorPermissions);

        $teamLeaderPermissions = [
            // Employee Management
            'view employees', 'view departments', 'view leaves',
            'view freelancer_projects', 'view types', 'view payment_types',
            'view operators', 'view statuses', 'view reasons',
            
            // Project Management
            'view assigned projects',
            'create projects', 'edit projects',
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
    }
} 