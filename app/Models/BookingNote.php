<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'title', 'note',
    ];
}
