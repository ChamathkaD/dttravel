<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    /**
     * The travel packages that belong to the conditions.
     */
    public function travelPackages(): BelongsToMany
    {
        return $this->belongsToMany(TravelPackage::class);
    }
}
