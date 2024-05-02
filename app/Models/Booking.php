<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    const STATUS_IN_PROGRESS = 'InProgress';

    const STATUS_COMPLETED = 'Completed';

    const STATUS_CANCELED = 'Cancel';

    protected $fillable = ['created_at', 'order_no'];

    protected $guarded = [];

    protected $casts = [
        'price_per_adult' => 'float',
        'price_per_child' => 'float',
        'total_child_price' => 'float',
        'total_adults_price' => 'float',
        'adult_tax' => 'float',
        'child_tax' => 'float',
        'adult_net_price' => 'float',
        'child_net_price' => 'float',
        'amount' => 'float',
        'total_tax' => 'float',
        'net_amount' => 'float',
        //        'total_price' => 'float',
    ];

    /**
     * Get the formatted price attribute.
     *
     * @return string
     */
    public function getPricePerAdultAttribute()
    {
        return number_format($this->attributes['price_per_adult'], 2);
    }

    public function getPricePerChildAttribute()
    {
        return number_format($this->attributes['price_per_child'], 2);
    }

//    public function getTotalChildPriceAttribute()
//    {
//        return number_format($this->attributes['total_child_price'], 2);
//    }
//
//    public function getTotalAdultsPriceAttribute()
//    {
//        return number_format($this->attributes['total_adults_price'], 2);
//    }

    public function getAdultTaxAttribute()
    {
        return number_format($this->attributes['adult_tax'], 2);
    }

    public function getChildTaxAttribute()
    {
        return number_format($this->attributes['child_tax'], 2);
    }

//    public function getAdultNetPriceAttribute()
//    {
//        return number_format($this->attributes['adult_net_price'], 2);
//    }
//
//    public function getChildNetPriceAttribute()
//    {
//        return number_format($this->attributes['child_net_price'], 2);
//    }

    public function getAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2);
    }

    public function getTotalTaxAttribute()
    {
        return number_format($this->attributes['total_tax'], 2);
    }

//    public function getNetAmountAttribute()
//    {
//        return number_format($this->attributes['net_amount'], 2);
//    }

//    public function getTotalPriceAttribute()
//    {
//        return number_format($this->attributes['total_price'], 2);
//    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->toDayDateTimeString(),
        );
    }

    /**
     * Get the agent user that owns the booking.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the traveler user that owns the booking.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Traveler');
            });
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'travel_package_id');
    }

    public function bookingTravelers(): HasMany
    {
        return $this->hasMany(BookingTraveler::class);
    }

    /**
     * @return void
     * Scope a query to only include in-progress bookings.
     */
    public function scopeInProgress(Builder $query): void
    {
        $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * @return void
     * Scope a query to only include completed bookings.
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * @return void
     * Scope a query to only include cancelled bookings.
     */
    public function scopeCancel(Builder $query): void
    {
        $query->where('status', self::STATUS_CANCELED);
    }

    public function bookingNotes(): HasMany
    {
        return $this->hasMany(BookingNote::class);
    }
}
