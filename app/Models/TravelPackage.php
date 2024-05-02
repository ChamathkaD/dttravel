<?php

namespace App\Models;

use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TravelPackage extends Model
{
    use HasFactory, HasPhoto, SoftDeletes;

    protected $fillable = [
        'travel_destination_id', 'travel_category_id', 'name', 'description', 'adults_price', 'child_price', 'discounted_price', 'tax', 'min_pax', 'max_pax', 'is_charge_tax', 'top_rated', 'meta_title', 'meta_description', 'meta_keyphrase', 'meta_image',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'meta_image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_charge_tax' => 'boolean',
    ];

    /**
     * Get the images for the travel package.
     */
    public function travelPackageImages(): HasMany
    {
        return $this->hasMany(TravelPackageImage::class, 'travel_package_id');
    }

    /**
     * Get the itineraries for the travel package.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'travel_package_id');
    }

    /**
     * The inclusions that belong to the travel package.
     */
    public function inclusions(): BelongsToMany
    {
        return $this->belongsToMany(
            Inclusion::class,
            'travel_package_inclusion',
            'travel_package_id',
            'inclusion_id'
        );
    }

    /**
     * The exclusions that belong to the travel package.
     */
    public function exclusions(): BelongsToMany
    {
        return $this->belongsToMany(Exclusion::class, 'exclusion_travel_package', 'travel_package_id', 'exclusion_id');
    }

    /**
     * The value adds that belong to the travel package.
     */
    public function valueAdds(): BelongsToMany
    {
        return $this->belongsToMany(ValueAdd::class, 'travel_package_value_add', 'travel_package_id', 'value_add_id');
    }

    /**
     * Get the conditions for the travel package.
     */
    public function conditions(): BelongsToMany
    {
        return $this->belongsToMany(Condition::class, 'condition_travel_package', 'travel_package_id', 'condition_id');
    }

    /**
     * Get the URL to the meta image.
     */
    public function metaImageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return Storage::disk('public')->url($this->meta_image);
        });
    }

    /**
     * Get the travel category that owns the travel package.
     */
    public function travelCategory(): BelongsTo
    {
        return $this->belongsTo(TravelCategory::class);
    }

    /**
     * Get the travel destination that owns the travel package.
     */
    public function travelDestination(): BelongsTo
    {
        return $this->belongsTo(TravelDestination::class);
    }

    /**
     * Get the bookings for the travel package.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'travel_package_id');
    }

    /**
     * @return void
     * Scope a query to only include published travel packages.
     */
    public function scopeTopRated(Builder $query): void
    {
        $query->where('top_rated', true);
    }
}
