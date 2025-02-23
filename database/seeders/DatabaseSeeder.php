<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\Doctor;
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

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            CategoriesTableSeeder::class,
            ServicesTableSeeder::class,
            ClinicsTableSeeder::class,
            DoctorsTableSeeder::class,
        ]);

    }
}
