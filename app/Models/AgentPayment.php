<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'agent_id', 'total_sale', 'billing_date', 'b2b_amount', 'status',
    ];
}
