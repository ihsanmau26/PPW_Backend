<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\CheckupHistory;
use App\Models\PrescriptionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'prescription_date',
    ];

    /**
     * Get the user that owns the Prescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Get the doctor that owns the Prescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * Get all of the details for the Prescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptionDetails(): HasMany
    {
        return $this->hasMany(PrescriptionDetail::class, 'prescription_id', 'id');
    }

    /**
     * Get the history associated with the Prescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function history(): HasOne
    {
        return $this->hasOne(CheckupHistory::class, 'prescription_id', 'id');
    }
}
