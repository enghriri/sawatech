<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'clinic']);
        Role::create(['name' => 'doctor']);

        // Assign roles to existing users (modify IDs as needed)
        $admin = User::find(1);
        if ($admin) $admin->assignRole('admin');

        $clinic = User::find(2);
        if ($clinic) $clinic->assignRole('clinic');

        $doctor = User::find(3);
        if ($doctor) $doctor->assignRole('doctor');
    }
}
