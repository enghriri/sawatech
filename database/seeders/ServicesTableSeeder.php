<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\Category;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray(); 
        $clinicIds = Clinic::pluck('id')->toArray();

        $services = [
            'General Checkup',
            'Dental Cleaning',
            'X-ray',
            'Blood Test',
            'Physiotherapy'
        ];

        foreach ($services as $serviceName) {
            // Randomly associate each service with a clinic and category
            Service::create([
                'name' => $serviceName,
                'clinic_id' => $clinicIds[array_rand($clinicIds)], 
                'category_id' => $categoryIds[array_rand($categoryIds)], 
            ]);
        }
    }
}
