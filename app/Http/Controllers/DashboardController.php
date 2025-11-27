<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventType;
use App\Models\TicketType;
use App\Models\Tickets;




class DashboardController extends Controller
{
    //
    function dashboard()
    {
        $userId = auth()->id();
        $allEvents=Event::where('eventDate','>',date('Y-m-d'))->where('userId', '=', $userId)->orderBy('eid','desc')->get();
        $eventType=EventType::all();
        $TicketTypes =  TicketType::get();

        $upcomingEvents = Event::where('eventDate','>',date('Y-m-d'))->where('userId', '=', $userId)->get()->pluck('eid')->toArray();

        $totalTickets = Tickets::whereIn('eventId',$upcomingEvents)->where('userId', '=', $userId)->count();
        $totalTicketsSold = Tickets::whereIn('eventId',$upcomingEvents)->where('userId', '=', $userId)->where('holder_name','!=','')->count();

        return view('pages.dashboard', compact('allEvents','eventType','TicketTypes','totalTickets', 'totalTicketsSold'));
    }
    
}
