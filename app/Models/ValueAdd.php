<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ValueAdd extends Model
{
    use HasFactory, HasPhoto;

    protected $fillable = ['title', 'icon'];

    /**
     * The travel packages that belong to the value add.
     */
    public function travelPackages(): BelongsToMany
    {
        return $this->belongsToMany(TravelPackage::class);
    }
}
