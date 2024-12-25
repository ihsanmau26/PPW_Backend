<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'shift_id',
    ];
}
