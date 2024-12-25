<?php

namespace App\Models;

use App\Models\PrescriptionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description'];

    /**
     * Get all of the prescriptionDetails for the Medicine
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptionDetails(): HasMany
    {
        return $this->hasMany(PrescriptionDetail::class, 'medicine_id', 'id');
    }
}
