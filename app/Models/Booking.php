<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'booking_date',
        'booking_type',
        'booking_slot',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }
}
