<?php

namespace App\Models;

use App\Models\User;
use App\Models\Shift;
use App\Models\Checkup;
use App\Models\DoctorShift;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
    ];

    /**
     * Get the user that owns the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * The roles that belong to the Doctor
     *
     * @return BelongsToMany
     */
    public function shifts(): BelongsToMany
    {
        return $this->belongsToMany(Shift::class, 'doctor_shifts', 'doctor_id', 'shift_id', 'id', 'id');
    }

    /**
     * Get all of the checkups for the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkups(): HasMany
    {
        return $this->hasMany(Checkup::class, 'doctor_id', 'id');
    }

    /**
     * Get all of the comments for the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'doctor_id', 'id');
    }
}
