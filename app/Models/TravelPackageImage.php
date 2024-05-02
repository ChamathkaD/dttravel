<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TravelPackageImage extends Model
{
    use HasFactory, HasPhoto;

    protected $fillable = [
        'travel_package_id', 'image_path',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'travel_image_url',
    ];

    /**
     * Get the URL to the meta image.
     */
    public function travelImageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return Storage::disk('public')->url($this->image_path);
        });
    }
}
