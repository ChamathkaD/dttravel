<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\NewResetPassword;
use App\Traits\HasPhoto;
use App\Traits\HasProfilePhoto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasPhoto, HasProfilePhoto, HasRoles, Notifiable, SoftDeletes;

    const STATUS_INVITED = 'Invited';

    const STATUS_PENDING = 'Pending';

    const STATUS_ACTIVE = 'Active';

    const STATUS_INACTIVE = 'Inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agent_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'contact',
        'emergency_contact1',
        'emergency_contact2',
        'profile_photo_path',
        'agent_commission_rate',
        'business_name',
        'business_reg_no',
        'business_contact',
        'business_email',
        'address',
        'country',
        'business_city',
        'business_logo_path',
        'call_name',
        'dob',
        'gender',
        'passport_id',
        'date_of_delivery',
        'date_of_expire',
        'language',
        'whatsapp',
        'instagram',
        'tiktok',
        'facebook',
        'drug_status',
        'food_status',
        'diet',
        'medication',
        'particular',
        'note',
        'country',
        'state',
        'zip',
        'last_login_at',
        'is_ban',
        'isMustChangePassword',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'isMustChangePassword' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'full_name',
        'role_name',
        'business_logo_url',
        'created_at_formatted',
    ];

    /**
     * Get the user's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }

    /**
     * Get the user's role
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles ? $this->roles->pluck('name')[0] : null
        );
    }

    /**
     * Get the invitation token associated with the user.
     */
    public function invitationToken(): HasOne
    {
        return $this->hasOne(InvitationToken::class);
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function businessLogoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return Storage::disk('public')->url($this->business_logo_path);
        });
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->toFormattedDateString(),
        );
    }

    /**
     * Get the traveler relations for the user (Traveler Only).
     */
    public function travelerRelations(): HasMany
    {
        return $this->hasMany(TravelerRelation::class);
    }

    /**
     * Get the bookings for the Traveler.
     */
    public function travelerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'traveler_id');
    }

    /**
     * Get the bookings for the Agent.
     */
    public function agentBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'agent_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function travelers(): HasMany
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new NewResetPassword($token));
    }
}
