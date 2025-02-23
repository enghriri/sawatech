<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio', 'profile_picture', 'clinic_id', 'working_hours'];

    protected $casts = [
        'working_hours' => 'array', 
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Optionally, link the doctor to services, if needed in the future
    // public function services()
    // {
    //     return $this->belongsToMany(Service::class, 'doctor_service');
    // }
}
