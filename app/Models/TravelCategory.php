<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\NodeTrait;

class TravelCategory extends Model
{
    use HasFactory, HasPhoto, NodeTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'slug', 'category_image_path', 'description',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'category_image_url',
    ];

    /**
     * Get the URL to the category image.
     */
    public function categoryImageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return Storage::disk('public')->url($this->category_image_path);
        });
    }

    /**
     * Get the travel packages for the travel category.
     */
    public function travelPackages(): HasMany
    {
        return $this->hasMany(TravelPackage::class);
    }
}
