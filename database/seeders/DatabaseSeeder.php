<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create the admin user first
        User::create([
            'username' => 'admin',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'first_name' => 'Test',
            'last_name' => 'User',
            'is_active' => true,
            'role' => 'director',
        ]);

        $this->call([
            RoleSeeder::class,          // Seed roles first
            UserSeeder::class,          // Then create users (including managers)
            DepartmentSeeder::class,    // Then create departments and assign managers
            SimpleCalendarSeeder::class, // Other seeders last
        ]);
    }
}
