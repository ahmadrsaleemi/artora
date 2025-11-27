<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventDetails;
use App\Models\EventType;
use App\Models\User;
use App\Models\TicketType;
use App\Models\EventTicketType;
use App\Models\Tickets;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    function addEvent()
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        // if($userType == 1)
        // {
        //     $allEvents=Event::where('userId','=',$userId)->orderBy('eid','desc')->get();
        // }
        // else
        // {
        //     $allEvents=Event::orderBy('eid','desc')->get();
        // }

        $allEvents = Event::when($userType == 1, function ($query) use ($userId) {
            return $query->where('userId', $userId);
        })->orderBy('eid', 'desc')->get();

        $eventType=EventType::all();
        $TicketTypes =  TicketType::get();
        return view('pages.event.addEvent', compact('allEvents','eventType','TicketTypes'));
    }
   

    function registerEvent(Request $request)
    {
        if (TicketType::count() == 0) {
            return redirect()->back()->with('message', 'Please Add Ticket Types First!');
        }
        
        $createEvent=new Event;
        $createEvent->title=$request->eventTitle;
        $createEvent->description=$request->eventDescription;
        $createEvent->address=$request->eventAddress;
        $createEvent->contactNumber=$request->eventContactNumber;
        $createEvent->contactEmail=$request->eventContactEmail;
        $createEvent->eventOrganizerId=Auth::id();
        $createEvent->eventStatus=0;
        $createEvent->ticketStatus=0;
        $createEvent->eventDate=$request->eventDate;
        $createEvent->headline=$request->headline;
        $createEvent->endDate=$request->endDate;
        $createEvent->venue_name=$request->venue_name;
        $createEvent->venue_address=$request->venue_address;
        $createEvent->event_organizer_Description=$request->event_organizer_Description;
        $createEvent->event_organizer_phone=$request->event_organizer_phone;
        $createEvent->event_organizer_email=$request->event_organizer_email;
        $createEvent->event_organizer_website=$request->event_organizer_website;
        $createEvent->event_organizer_social_media=$request->event_organizer_social_media;
        $createEvent->userId = auth()->id();
        $createEvent->save();
        $eventId=$createEvent->eid;

        $eventDetails=new EventDetails;
        $eventDetails->numberOfTickets = isset($request->noOfTickets) && !empty($request->noOfTickets) ? $request->noOfTickets : 0;
        $eventDetails->eventAccess=isset($request->eventAccess) && !empty($request->eventAccess) ? $request->eventAccess : 0;
        $eventDetails->ticketPrice=isset($request->ticketPrice) && !empty($request->ticketPrice) ? $request->ticketPrice : 0;
        $eventDetails->eventType=isset($request->eventType) && !empty($request->eventType) ? $request->eventType : '';
        $eventDetails->seatArrangement=isset($request->seatArrange) && !empty($request->seatArrange) ? $request->seatArrange : '';
        $eventDetails->ticketDivision=isset($request->ticketDivision) && !empty($request->ticketDivision) ? $request->ticketDivision : 0;
        $eventDetails->eid=$eventId;
        $eventDetails->userId = auth()->id();
        //$eventDetails->save();

        $ticketDivision = $request->ticketDivision;
        $seatingPlan = [];
        
        $total = 0;
        $tableNumber = 1;
        foreach ($request->typeType['type'] as $i => $id)
        {
            $_TicketType = TicketType::find($id);
            $totalTablesToBeCreated = ceil($request->typeType['quantity'][$i] / $ticketDivision);
            for($j = 1; $j <= $totalTablesToBeCreated; $j++)
            {
                $seatingPlan[$tableNumber] = [
                    "name" => "Not Applicable",
                    "number" => $tableNumber,
                    "capacity" => $ticketDivision,
                    "tableType" => $_TicketType->ticketType
                ];
                $tableNumber += 1;
            }

            $eventTicketType = new EventTicketType();
            $eventTicketType->ticketType = $_TicketType->ticketType ?? '';
            $eventTicketType->event_id = $eventId;
            $eventTicketType->type_id = $id;
            $eventTicketType->tickets = $request->typeType['quantity'][$i];
            $eventTicketType->price = $request->typeType['price'][$i];
            $eventTicketType->platform = 0;
            $eventTicketType->userId = auth()->id();
            $eventTicketType->save();
            $total += $request->typeType['quantity'][$i];
        }

        
        $txtSeatingPlan = json_encode($seatingPlan, true);
        $eventDetails->seatingplan = $txtSeatingPlan;

        $eventDetails->numberOfTickets = $total;
        $eventDetails->save();
        


        return redirect()->back()->with('message', 'Event Added successfully!');

    }

    public function deleteTable(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $tableNumber = $request->tableNum;
        $eventId = $request->eventId;

        $eventDetails = EventDetails::where('eid',$eventId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', $userId);
        })->first();

        $seatingPlan = json_decode($eventDetails->seatingplan, true);
        unset($seatingPlan[$tableNumber]);
        $eventDetails->seatingplan = json_encode($seatingPlan);

        // var_dump($seatingPlan);

        $seatingOccupied = json_decode($eventDetails->seatingOccupied, true);
        $seats = $seatingOccupied[$tableNumber];
        unset($seatingOccupied[$tableNumber]);

        $eventDetails->seatingOccupied = json_encode($seatingOccupied);

        $eventDetails->save();

        // $tickets = Tickets::where('eventId', $eventId)->when($userType == 1, function ($query) use ($userId){
        //     return $query->where('userId', $userId);
        // })->first();

        if (!empty($seats)) {
            Tickets::where('eventId', $eventId)
                ->when($userType == 1, function ($query) use ($userId) {
                    return $query->where('userId', $userId);
                })
                ->whereIn('seatNumber', $seats) // Filter tickets with seat numbers in the array
                ->delete(); // Delete all matched tickets
        }

    }

    function updateEventPage($id)
    {
        $eventData=Event::where('eid',$id)->get();
        $eventDetailsData=EventDetails::where('eid',$id)->get();
        $eventType=EventType::all();
        $TicketTypes =  TicketType::get();
        return view('pages.event.updateEventPage', compact('eventData','eventDetailsData','eventType','TicketTypes'));
    }

    function addSeatsToTable(Request $request)
    {
        $eventId = $request->eventId;
        $updateSeats = $request->updateSeats;
        $tableNumber = $request->tableNumber;
        $newSeats = $request->newSeats;
        
        //find latest ticketnumber
        $ticket = Tickets::where('eventId', $eventId)->orderBy('ticketId', 'desc')->first();
        $ticketNumber = $ticket->ticketNumber;
        
        $eventDetails = EventDetails::where('eid',$eventId)->first();
        $userId = $eventDetails->userId;

        $seatArrangement = $eventDetails->seatArrangement;
        if($seatArrangement == "grid")
        {
            $sectionType = "C";
        }
        else
        {
            $sectionType = "T";
        }

        $directory = "qrcodes/event_{$eventId}";

        if (!Storage::disk('public')->exists($directory)) 
        {
            Storage::disk('public')->makeDirectory($directory);
        }
    
        if($eventDetails)
        {
            $seatingPlan = json_decode($eventDetails->seatingplan, true);
            if(isset($seatingPlan[$tableNumber]))
            {
                $uptoSeatNumbers = $seatingPlan[$tableNumber]["capacity"];
                
                for($i = 1; $i <= $newSeats; $i++)
                {
                    $currentSeatNumber = $uptoSeatNumbers + $i;
                    $place = $sectionType . $tableNumber . "S" . $currentSeatNumber;

                    //find new ticket number;
                    $ticketNumber += 1;
                    
                    
                    //write a query to add new ticket
                    $ticket = new Tickets();
                    $ticket->eventId = $eventId;
                    $ticket->ticketNumber = $ticketNumber;
                    $ticket->holder_name = "";
                    $ticket->leader_name = "";
                    $ticket->seatNumber = "";
                    $ticket->ticketStatus = "active";
                    $ticket->holder_email = "";
                    $ticket->tickettype_id = "0";
                    $ticket->tickettype_name = $seatingPlan[$tableNumber]["tableType"];
                    $ticket->scaned = 0;
                    $ticket->price = 0;
                    $ticket->userId = $userId;
                    $ticket->save();
                    
                    $qrCodeData = json_encode([
                        "event_id" => $eventId,
                        "ticket_number" => $ticketNumber
                    ]);

                    $fileName = "ticket_{$ticketNumber}.png";
                    $filePath = "{$directory}/{$fileName}";

                    QrCode::format('png')->size(300)->generate($qrCodeData, storage_path("app/public/{$filePath}"));
                }

                $seatingPlan[$tableNumber] = [
                    "name" => $seatingPlan[$tableNumber]["name"],
                    "number" => $tableNumber,
                    "capacity" => $updateSeats,
                    "tableType" => $seatingPlan[$tableNumber]["tableType"]
                ];
                $eventDetails->seatingplan = json_encode($seatingPlan);
                $eventDetails->save();
                $message = 'Seats on table "' . $tableNumber. '" has been updated!';

                return response()->json([
                    'status' => 'success',
                    'message' => $message
                ], 200);
            }
            else
            {
                $message = "Create Table first!";
            }
        }
        else
        {
            $message = "Contact Support! Event details not found";
        }

        return response()->json([
            'status' => 'error',
            'message' => $message
        ], 400);
        
    }

    function updateEvent(Request $request)
    {
        $eventId=$request->eventId;
        $updateEvent=Event::where('eid',$eventId)->first();
        $updateEvent->title=$request->eventTitle;
        $updateEvent->description=$request->eventDescription;
        $updateEvent->address=$request->eventAddress;
        $updateEvent->contactNumber=$request->eventContactNumber;
        $updateEvent->contactEmail=$request->eventContactEmail;
        $updateEvent->ticketStatus=0;
        $updateEvent->headline=$request->headline;
        $updateEvent->endDate=$request->endDate;
        $updateEvent->venue_name=$request->venue_name;
        $updateEvent->venue_address=$request->venue_address;
        $updateEvent->event_organizer_Description=$request->event_organizer_Description;
        $updateEvent->event_organizer_phone=$request->event_organizer_phone;
        $updateEvent->event_organizer_email=$request->event_organizer_email;
        $updateEvent->event_organizer_website=$request->event_organizer_website;
        $updateEvent->event_organizer_social_media=$request->event_organizer_social_media;
        $updateEvent->save();
        
   
        $eventDetails=EventDetails::where('eid',$eventId)->first();
        $eventDetails->eventAccess=isset($request->eventAccess) && !empty($request->eventAccess) ? $request->eventAccess : 0;
        $eventDetails->eventType=isset($request->eventType) && !empty($request->eventType) ? $request->eventType : '';
        $eventDetails->seatArrangement=isset($request->seatArrange) && !empty($request->seatArrange) ? $request->seatArrange : '';
        $eventDetails->ticketDivision=isset($request->ticketDivision) && !empty($request->ticketDivision) ? $request->ticketDivision : 0;

        //add seatingplan again
        
        EventTicketType::where('event_id',$eventId)->delete();
        Tickets::where('eventId',$eventId)->delete();

        $tableNumber = 1;
        $seatingPlan = [];

        $ticketDivision = $request->ticketDivision;

        $total = 0;
        foreach ($request->typeType['type'] as $i => $id) {
            $_TicketType = TicketType::find($id);
            $eventTicketType = new EventTicketType();
            $eventTicketType->ticketType = $_TicketType->ticketType ?? '';
            $eventTicketType->event_id = $eventId;
            $eventTicketType->type_id = $id;
            $eventTicketType->tickets = $request->typeType['quantity'][$i];
            $eventTicketType->price = $request->typeType['price'][$i];
            $eventTicketType->save();
            $total += $request->typeType['quantity'][$i];

            $totalTablesToBeCreated = ceil($request->typeType['quantity'][$i] / $ticketDivision);
            for($j = 1; $j <= $totalTablesToBeCreated; $j++)
            {
                $seatingPlan[$tableNumber] = [
                    "name" => "Not Applicable",
                    "number" => $tableNumber,
                    "capacity" => $ticketDivision,
                    "tableType" => $_TicketType->ticketType
                ];
                $tableNumber += 1;
            }
        }

        $eventDetails->numberOfTickets = $total;

        $txtSeatingPlan = json_encode($seatingPlan, true);
        $eventDetails->seatingplan = $txtSeatingPlan;
        $eventDetails->seatingOccupied = "";

        $eventDetails->save();
       
        return redirect()->back()->with('message', 'Event Updated successfully!');
      
    }

    function eventTypePage()
    {
        return view('pages.event.eventType');
    }
    function addEventType(Request $request)
    {
        $existingType = EventType::where('eventType', strtoupper($request->eventType))->first();
        
        if($existingType)
        {
        return redirect()->back()->with("message","Event type ".$request->eventType." Already Added!");
        }
        else
        {
            $createEventType=new EventType;
        $createEventType->eventType=strtoupper($request->eventType);
        $createEventType->save();
        return redirect()->back()->with("message","Event type Added SuccessFully!");
        }
    }

    function deleteEvent($id)
    {
        $eventDelete= Event::find($id);
        if($eventDelete->delete())
        {
        $eventDetailDelete= EventDetails::where('eid',$id);
        $eventDetailDelete->delete();

            return redirect()->back()->with("message","Event Deleted Successfully!");
        }
        else
        {
            return redirect()->back()->with("message","Event Not Deleted Successfully. Try Again!");
        }
    }
}
