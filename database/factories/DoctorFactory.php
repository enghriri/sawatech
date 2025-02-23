<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doctor;
use App\Models\Clinic;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'bio' => $this->faker->paragraph(),
            'profile_picture' => 'default_profile.png',
            'clinic_id' => Clinic::inRandomOrder()->first()->id, 
            'working_hours' => json_encode([
                'monday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
                'tuesday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
                'wednesday' => ['9:00 AM - 12:00 PM', '2:00 PM - 5:00 PM'],
            ]),
        ];
    }
}
