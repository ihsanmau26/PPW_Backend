<?php

namespace App\Models;

use App\Models\Checkup;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckupHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkup_id',
        'prescription_id',
        'diagnosis',
        'notes',
    ];

    /**
     * Get the checkup that owns the CheckupHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkup(): BelongsTo
    {
        return $this->belongsTo(Checkup::class, 'checkup_id', 'id');
    }

    /**
     * Get the prescription that owns the CheckupHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'id');
    }
}
