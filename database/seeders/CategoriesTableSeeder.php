<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['General', 'Dentistry', 'Pediatrics', 'Orthopedics', 'Cardiology'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}

