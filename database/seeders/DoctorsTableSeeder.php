<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Clinic;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = Clinic::all();

        foreach ($clinics as $clinic) {
            Doctor::create([
                'name' => $clinic->name . ' Doctor',
                'bio' => 'This is a brief bio of the doctor.',
                'profile_picture' => 'default_profile.png',
                'clinic_id' => $clinic->id,
                'working_hours' => json_encode([
                    'monday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
                    'tuesday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
                    'wednesday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
    
                ]),
            ]);
        }
    }
}
