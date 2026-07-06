<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public mixed $available_tickets;
    protected $table = 'events';

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
