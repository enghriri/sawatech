<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Category;

class ClinicsTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            Clinic::create([
                'name' => $category->name . ' Clinic',
                'category_id' => $category->id,
                'logo' => 'default_logo.png', // Default image (can replace later)
            ]);
        }
    }
}
