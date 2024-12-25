<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\CheckupHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checkup extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'checkup_type',
        'checkup_date',
        'checkup_time',
        'status',
    ];

    /**
     * Get the user that owns the Checkup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Get the user that owns the Checkup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
    
    /**
     * Get all of the comments for the Checkup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkup_histories(): HasMany
    {
        return $this->hasMany(CheckupHistory::class, 'checkup_id', 'id');
    }
    
    /**
     * Get the prescription associated with the Checkup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function prescription(): HasOne
    {
        return $this->hasOne(Prescription::class, 'patient_id', 'patient_id')
                    ->where('doctor_id', $this->doctor_id);
    }
}
