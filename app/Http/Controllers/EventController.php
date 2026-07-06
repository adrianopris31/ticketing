<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('bookings')->get();

        return Inertia::render('Ticketing', ['events' => $events]);
    }
}
