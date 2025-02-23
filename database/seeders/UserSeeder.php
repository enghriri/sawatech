<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $clinicRole = Role::firstOrCreate(['name' => 'clinic']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);

        // Create Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Clinic User
        $clinic = User::firstOrCreate([
            'email' => 'clinic@example.com'
        ], [
            'name' => 'Clinic User',
            'password' => Hash::make('password'),
        ]);
        $clinic->assignRole($clinicRole);

        // Create Doctor User
        $doctor = User::firstOrCreate([
            'email' => 'doctor@example.com'
        ], [
            'name' => 'Doctor User',
            'password' => Hash::make('password'),
        ]);
        $doctor->assignRole($doctorRole);

        $this->command->info('Admin, Clinic, and Doctor users created successfully!');
    }
}
