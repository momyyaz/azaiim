<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use App\Event;

class CalendarController extends Controller
{
    public function index()
    {
        // dd('reham');
        return view('web-views.calendar');
    }
    
    public function getEvents()
    {
        $events = Event::all();

        $eventList = [];

        foreach ($events as $event) {
            $eventList[] = Calendar::event(
                $event->title,
                true,
                new \DateTime($event->start_date),
                new \DateTime($event->end_date . ' +1 day')
            );
        }

        $calendar = Calendar::addEvents($eventList);

        return response()->json($calendar);
    }

    public function storeEvent(Request $request)
    {
        // Handle storing of new events in the database
    }
}