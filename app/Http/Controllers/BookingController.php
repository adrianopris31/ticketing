<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show($id)
    {
       $event = Event::where('id', $id)->with(['bookings' => function ($query) {
            return $query->where('user_id', auth()->user()->id);
        }])->firstOrFail();

        return response()->json($event);
    }

    public function store(Event $event)
    {
        if($event->tickets_left > 0){
            Booking::create([
                'user_id' => auth()->user()->id,
                'event_id' => $event->id,
            ]);
            $event->tickets_left--;
            $event->save();

            return response()->json(['success' => 'You have successfully booked this ticket.']);
        }

        return response()->json(['failed' => 'Tickets sold.']);
    }
}
