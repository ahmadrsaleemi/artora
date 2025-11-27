<?php

namespace App\Http\Controllers;

use DB;
use DNS2D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Tickets;
use App\Models\Event;
use App\Models\EventDetails;
use App\Models\TicketType;
use App\Models\EventTicketType;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketsController extends Controller
{
    //generate ticket function is not used anymore
    public function generateTicket(Request $request)
    {
        if (TicketType::count() == 0)
        {
            return response()->json('message', 'Please Add Ticket Types First!');
        }
        $eventDetails = EventDetails::where('eid', $request->eventId)->first();
        $events = Event::find($request->eventId);
        $event_id = $request->eventId;

        $seatingPlan = json_decode($eventDetails->seatingplan, true);

        $directory = "qrcodes/event_{$event_id}";
        Storage::disk('public')->makeDirectory($directory);
        // Storage::makeDirectory($directory);

        $tickets = [];

        $qrCodeData = [];

        $ticketnumber = 1;

        $ticketDetails = EventTicketType::where('event_id', $request->eventId)->get();

        foreach($seatingPlan as $section => $details)
        {
            for ($i = 1; $i <= $details["capacity"]; $i++)
            {
                $qrCodeData = json_encode([
                    "event_id" => $request->eventId,
                    "ticket_number" => $ticketnumber
                ]);

                $fileName = "ticket_{$ticketnumber}.png";
                $filePath = "{$directory}/{$fileName}";

                QrCode::format('png')->size(300)->generate($qrCodeData, storage_path("app/public/{$filePath}"));

                $createTickets = new Tickets;
                $createTickets->eventId = $request->eventId;
                $createTickets->tickettype_id = $ticket["type_id"] ?? 0;
                $createTickets->tickettype_name = $details["tableType"] ?? '';
                $createTickets->price = $ticket["price"] ?? 0;
                $createTickets->platform = 0;
                $createTickets->ticketNumber = $ticketnumber;
                $createTickets->ticketStatus = "active";
                $createTickets->holder_name = "";
                $createTickets->holder_email = "";
                $createTickets->seatNumber = "";
                $createTickets->save();
                $ticketnumber += 1;

                if($ticketnumber % 15 == 0)
                {
                    sleep(5);
                }
            }
        }

        $events->ticketStatus = 1;
        $events->save();
        return response()->json('success');
    }

    public function importTicket(Request $request)
    {

        // $eventTicketsDetails = EventTicketType::where('event_id', $request->eventId)->get();
        $eventId = $request->eventId;
        $eventDetails = EventDetails::where('eid', $request->eventId)->first();
        // $seatingPlan = json_decode($eventDetails->seatingplan, true);
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
        Storage::disk('public')->makeDirectory($directory);


        $file = $request->file('csvFile');

        $filePath = $file->getRealPath();
        $handle = fopen($filePath, "r");

        $header = fgetcsv($handle, 1000, ","); // Read CSV header
        $data = [];

        $tickets = [];
        $sectionNumber = 1;
        $seatingPlan = [];
        $seatingOccupied = [];

        $currentTableNumber = 0;
        $currentSeatNumber = 1;

        // Read each row of the CSV
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            $data[] = $row;
            if(!empty($row[1]) && !empty($row[3]) && !empty($row[2]) && !empty($row[5]))
            {
                if($currentTableNumber == $row[3])
                {
                    $currentSeatNumber += 1;
                }
                else
                {
                    $currentSeatNumber = 1;
                    $currentTableNumber = $row[3];
                }

                $place = $sectionType . $currentTableNumber . "S" . $currentSeatNumber;
                
                if(!isset($seatingPlan[$row[3]]))
                {
                    $seatingPlan[$row[3]] = [
                        "name" => $row[4],
                        "number" => $row[3],
                        "capacity" => 1,
                        "tableType" => $row[1]
                    ];
                }
                else
                {
                    $seatingPlan[$row[3]]["capacity"] += 1;
                }
                
                $seatingOccupied[$row[3]][] = $place;

                $tickets[$row[3]][] = [
                    "ticketType" => $row[1],
                    "sectionNumber" => $row[3],
                    "guestName" => $row[2],
                    "leaderName" => $row[5],
                    "seatNumber" => $place
                ];
            }
            else
            {
                echo "Error found in csv file.";
                exit();
            }
        }

        fclose($handle);

        $ticketNumber = 1;
        foreach ($tickets as $table => $ticketsdetails)
        {
            foreach($ticketsdetails as $tableNumber => $ticketdetails)
            {
                // echo "my table number $table has " . $ticketdetails["guestName"] . " will get  " . $ticketdetails["seatNumber"] ."</br>";
                
                $ticket = new Tickets();
                $ticket->eventId = $eventId;
                $ticket->ticketNumber = $ticketNumber;
                $ticket->holder_name = $ticketdetails["guestName"];
                $ticket->leader_name = $ticketdetails["leaderName"];
                $ticket->seatNumber = $ticketdetails["seatNumber"];
                $ticket->ticketStatus = "active";
                $ticket->holder_email = "pending@ets.com";
                $ticket->tickettype_id = "0";
                $ticket->tickettype_name = $ticketdetails["ticketType"];
                $ticket->scaned = 0;
                $ticket->price = 0;
                $ticket->userId = auth()->id();
                $ticket->save();
                
                $qrCodeData = json_encode([
                    "event_id" => $eventId,
                    "ticket_number" => $ticketNumber
                ]);
                
                $fileName = "ticket_{$ticketNumber}.png";
                $filePath = "{$directory}/{$fileName}";
                
                QrCode::format('png')->size(300)->generate($qrCodeData, storage_path("app/public/{$filePath}"));
                
                $ticketNumber += 1;
            }
        }
        
        $txtSeatingPlan = json_encode($seatingPlan);
        $txtSeatingOccupied = json_encode($seatingOccupied);
        
        $eventDetails->seatingplan = $txtSeatingPlan;
        $eventDetails->seatingOccupied = $txtSeatingOccupied;
        $eventDetails->save();

        echo "Tickets generated successfully!";
        exit();
    }

    function viewTicket()
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $allEvent = Event::when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', $userId);
        })->get();

        return view('pages.tickets.viewTicket', compact('allEvent'));
    }

    public function selectEventTickets(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;

        $selectedEvent = Tickets::when($userType == 1, function ($query) use ($userId) {
            return $query->where('userId', '=', $userId);
        })->where('eventId', $request->eventId)->get();

        $eventTitle = Event::where('eid', $request->eventId)->when($userType == 1, function ($query) use ($userId) {
            return $query->where('userId', '=', $userId);
        })->first();

        if(empty($eventTitle))
        {
            return response()->json([
                'html' => "No tickets found!"
            ]);
        }

        $html = '';
        foreach ($selectedEvent as $ticket) {
            $imageSrc = asset('storage\\qrcodes\\event_' . $ticket->eventId . '\\ticket_' . $ticket->ticketNumber . '.png');
            $html .= '<tr>';
            $html .= '<td>' . $ticket->ticketId . '</td>';
            $html .= '<td>' . $eventTitle->title . '</td>';
            $html .= '<td>' . $ticket->tickettype_name . '</td>';
            $status = $ticket->seatNumber != '' ? '<span class="badge badge-warning">Assigned</span>' : '<span class="badge badge-success">Available</span>';
            $html .= '<td>' . $status . '</td>';
            $html .= '<td>' . $ticket->holder_name . '</td>';
            $html .= '<td>' . $ticket->leader_name . '</td>';
            $html .= '<td>' . $ticket->seatNumber . '</td>';

            $html .= '<td><img height=40 width=40 src="'. $imageSrc .'"</td>';

            // if ($ticket->seatNumber != "") {
            //     $qrCodeData = $ticket->ticketId . ' | ' . $ticket->seatNumber . ' | ' . $ticket->holder_email . ' | ' . $ticket->holder_name;
            //     $html .= '<td>' . DNS2D::getBarcodeHTML("$qrCodeData", 'QRCODE', 2, 2) . '

            // </td>';
            // } else {
            //     $html .= '<td></td>';
            // }
            $action = '';
            if ($ticket->seatNumber != '') $action = '<a href="' . route('ticket-PDF') . '?id=' . $ticket->ticketId . '" class="btn btn-primary">Generate PDF</a>
        <a onclick="UnAssigned(this,' . $ticket->ticketId . ')" class="btn btn-danger">Un-Assigned</a>';
            $html .= '<td>' . $action . '</td>';

            $html .= '</tr>';
        }

        $paginationHtml = '<div class="pagination-wrapper">';
        // $paginationHtml .= $selectedEvent->links();
        $paginationHtml .= '</div>';

        return response()->json([
            'html' => $html,
            'pagination' => $paginationHtml
        ]);
    }

    public function ticketAssign()
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $allEvent = Event::when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->get();
        return view('pages.tickets.ticketAssign', compact('allEvent'));
    }

    public function ScannedTickets(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $allEvent = Event::when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->get();

        $tickets = Tickets::when($userType == 1, function ($query) use ($userId){
            return $query->where('eventId', $request->id);
        })->where('scaned', '=', 1)->get();

        return view('pages.tickets.scaned_tickets', compact('allEvent', 'tickets'));
    }

    private function getSectionAndSeatNumber($seatExpression, $seatArrangement)
    {
        if($seatArrangement == "grid")
        {
            preg_match('/C(\d+)S/', $seatExpression, $matches);
            $sectionNumber = $matches[1] ?? null;
            $sectionName = "Column " . $sectionNumber;

            preg_match('/S(\d+)/', $seatExpression, $matches);
            $seatNumber = $matches[1] ?? null;
        }
        else
        {
            preg_match('/T(\d+)S/', $seatExpression, $matches);
            $sectionNumber = $matches[1] ?? null;
            $sectionName = "Table " . $sectionNumber;

            preg_match('/S(\d+)/', $seatExpression, $matches);
            $seatNumber = $matches[1] ?? null;
        }
        return [
            "section" => $sectionName,
            "seat" => "Seat " . $seatNumber
        ];
    }

    public function getEventTickets(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $eventTickets = Tickets::where('eventId', $request->eventId)->when($userType == 1, function($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->get();
        $html = '';
        if (count($eventTickets) > 0)
        {
            $eventDetails = EventDetails::where('eid', $request->eventId)->when($userType == 1, function($query) use ($userId){
                return $query->where('userId', '=', $userId); 
            })->first();

            $availableTables = json_decode($eventDetails->seatingplan,true);

            $numberOfOptions = intval($eventDetails->numberOfTickets / $eventDetails->ticketDivision);

            foreach ($eventTickets as $eventTicket)
            {
                $seatNumber = $eventTicket->seatNumber;
                $seatSetting = $this->getSectionAndSeatNumber($seatNumber, $eventDetails->seatArrangement);
                $html .= '<tr class="parent-container">';
                $html .= '<td>' . $eventTicket->ticketId . '</td>';
                $html .= '<td>' . $eventTicket->tickettype_name . '</td>';

                $readonly = "";
                if(!empty($eventTicket->holder_name) || !empty($eventTicket->leader_name))
                {
                    $readonly = "readonly";
                }

                if(!empty($eventTicket->holder_name))
                {
                    $html .= '<td><input type="text" name="holderName[]" value="' . $eventTicket->holder_name . '" ' . $readonly .' placeholder="N/A" class="form-control"></td>';
                }
                else
                {
                    $html .= '<td><input type="text" name="holderName[]" value="" placeholder="Enter guest name" '. $readonly .' class="form-control holderName"></td>';
                }
                if(!empty($eventTicket->leader_name))
                {
                    $html .= '<td><input type="text" name="leaderName[]" value="' . $eventTicket->leader_name . '" ' . $readonly .' placeholder="N/A" class="form-control"></td>';
                }
                else
                {
                    $html .= '<td><input type="text" name="leaderName[]" value="" placeholder="Enter leader name" '. $readonly .' class="form-control leaderName"></td>';
                }

                if ($eventDetails->seatArrangement == 'grid' && is_numeric($eventDetails->ticketDivision))
                {
                    if(empty($seatNumber))
                    {
                        $html .= '<td>';
                        $html .= '<select name="ticketDivision[]" class="form-control first-dropdown" data-number-of-options="' . $numberOfOptions . '">';
                        $html .= '<option value="">Select Column</option>'; // Default placeholder option
    
                        foreach($availableTables as $table => $numberofseats)
                        {
                            $html .= '<option value="' . $table . '">C' . $table . '</option>';
                        }
    
                        $html .= '</select>';
                        $html .= '</td>';
                    }
                    else
                    {
                        $html .= '<td><input type="text" name="seatNumberOccupied[]" value="' . $seatSetting["section"] . '" readonly class="form-control" disabled></td>';
                    }
                }
                if ($eventDetails->seatArrangement == 'roundTable' && is_numeric($eventDetails->ticketDivision))
                {
                    if(empty($seatNumber))
                    {
                        $html .= '<td>';
                        $html .= '<select name="ticketDivision[]" class="form-control first-dropdown" data-number-of-options="' . $numberOfOptions . '">';
                        $html .= '<option value="">Select Table</option>'; // Default placeholder option
    
                        if(!empty($availableTables))
                        {
                            foreach($availableTables as $table => $numberofseats)
                            {
                                $html .= '<option value="' . $table . '">T' . $table . '</option>';
                            }
                        }
    
                        // for ($i = 1; $i <= $eventDetails->ticketDivision; $i++)
                        // {
                        //     $html .= '<option value="T' . $i . '">T' . $i . '</option>';
                        // }
                        $html .= '</select>';
                        $html .= '</td>';
                    }
                    else
                    {
                        $html .= '<td><input type="text" name="sectionNumberOccupied[]" value="' . $seatSetting["section"]  . '" readonly class="form-control" disabled></td>';
                    }
                }

                // Placeholder for the second dropdown
                $html .= '<td>';
                if(empty($seatNumber))
                {
                    $html .= '<select onclick="OpenreassignSeat(this)" name="subSection[]" class="form-control second-dropdown">';
                    $html .= '<option  value="">Select a seat</option>'; // Default placeholder option
                    $html .= '</select>';
                }
                else
                {
                    $html .= '<input type="text" name="seatNumberOccupied[]" value="' . $seatSetting["seat"]  . '" readonly class="form-control" disabled>';
                }
                $html .= '</td>';

                $html .= '<td>';
                if ($eventTicket->seatNumber != '')
                {
                    
                    if(!empty($seatNumber))
                    {
                        $html .= '<button type="button" class="btn btn-success btn-sm" onclick="return ViewAssignTicket(this)" data-ticket-id="' . $eventTicket->ticketId . '" title="View Ticket" ><i class="fas fa-eye"></i></button>&nbsp;';
                    }
                    $html .= '<button type="button" class="btn btn-danger btn-sm" onclick="return unassignSeat(this)" data-event-id="'.$request->eventId.'" data-ticket-id="' . $eventTicket->ticketId . '" data-seatNumber="'.$seatNumber.'"  ><i class="fas fa-user-slash"></i></button>';
                    
                    $html .= '<button type="button" class="btn btn-warning px-1 " style="display: none;" onclick="return ReassignSeat(this)" data-ticket-id="' . $eventTicket->ticketId . '"  >Re-assign</button>';
                }
                else
                {
                    //$html .= '<button type="button" class="btn btn-primary assign-buttonn" onclick="return assignTicket(this)" data-ticket-id="' . $eventTicket->ticketId . '" data-price="' . $eventTicket->price . '" >Assign</button>';
                    $html .= '<button type="button" class="btn btn-primary assign-button btn-sm" data-event-id="'.$request->eventId.'" data-ticket-id="' . $eventTicket->ticketId . '" data-price="' . $eventTicket->price . '" disabled ><i class="fas fa-user-plus"></i></button>';
                }

                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        else
        {
            $html = '<tr><td colspan="7" class="text-center">No tickets found!</td></tr>';
        }
        return response()->json(['html' => $html]);
    }

    public function assignTicketForm(Request $request)
    {
        $tableNumber = $request->tableNumber;
        $ticket = Tickets::where('ticketId', $request->ticketId)->first();

        $this->assignTicketCore($request, $ticket);
    }

    public function assignTicketFormSeatingPlan(Request $request)
    {
        $seatNumber = $request->seatNumber;
        $eventId = $request->eventId;

        $checkSeatAllocated = Tickets::where('seatNumber', $seatNumber)->where('eventId', $eventId)->first();
        
        if(empty($checkSeatAllocated))
        {
            $foundSeat = Tickets::where(function ($query) {
                $query->whereNull('seatNumber')
                      ->orWhere('seatNumber', '');
                })
                ->where(function ($query) {
                    $query->whereNull('holder_name')
                        ->orWhere('holder_name', '');
                })
                ->where(function ($query) {
                    $query->whereNull('leader_name')
                        ->orWhere('leader_name', '');
                })
                ->where('eventId', $eventId)
                ->first();

            if($foundSeat)
            {
                $allocated = $this->assignTicketCore($request, $foundSeat);
                if($allocated)
                {
                    return redirect()->to(url('ticket/Seat-Plan?id=' . $eventId));
                }
                else
                {
                    $errorMessage = 'Something went wrong! Contact IT Team';
                }
            }
            else
            {
                $errorMessage = 'No free space found, empty seat before allocation!';
            }


        }
        else
        {
            $errorMessage = "seat already allocated!";
        }

        return response()->json([
            'status' => 'error',
            'message' => $errorMessage
        ], 400);
    }

    private function assignTicketCore($request, $ticket)
    {
        $tableNumber = $request->tableNumber;
        if ($ticket)
        {
            $eventDetails = EventDetails::where('eid', $ticket->eventId)->first();
            $occupiedSeats = json_decode($eventDetails->seatingOccupied, true);
            $occupiedSeats[$tableNumber][] = $request->seatNumber;
            
            if (isset($request->holder_name)) $ticket->holder_name = $request->holder_name;
            if (isset($request->leader_name)) $ticket->leader_name = $request->leader_name;
            if (isset($request->leader_email)) $ticket->holder_email = $request->leader_email;
            if (isset($request->seatNumber)) 
            {
                $ticket->seatNumber = $request->seatNumber;
            }
            if (isset($request->leader_phone)) $ticket->leader_phone = $request->leader_phone;
            if (isset($request->guest_phone)) $ticket->guest_phone = $request->guest_phone;
            if (isset($request->guest_email)) $ticket->guest_email = $request->guest_email;
            if (isset($request->amount)) $ticket->amount = $request->amount;
            $ticket->save();
            $eventDetails->seatingOccupied = $occupiedSeats;
            $eventDetails->save();
            // return redirect()->back()->with('message', 'Ticket assigned!');

            return true;
        } 
        else 
        {
            return false;
        }
    }


    public function assignTicket(Request $request)
    {
        $tableNumber = $request->tableNumber;
        $ticket = Tickets::where('ticketId', $request->ticketId)->first();
        
        // if ($ticket && isset($request->amount) && $ticket->price != $request->amount)
        // {
            //     return redirect()->back()->with('message', 'Amount Should be the ticket price.');
            // }
            
        if ($ticket) 
        {
            $eventDetails = EventDetails::where('eid', $ticket->eventId)->first();
            $occupiedSeats = json_decode($eventDetails->seatingOccupied, true);
            $occupiedSeats[$tableNumber][] = $request->seatNumber;
            
            if (isset($request->leader_name)) $ticket->leader_name = $request->leader_name;
            if (isset($request->holder_name)) $ticket->holder_name = $request->holder_name;
            // if (isset($request->leader_email)) $ticket->holder_email = $request->leader_email;
            if (isset($request->seatNumber)) 
            {
                $ticket->seatNumber = $request->seatNumber;
            }
            // if (isset($request->leader_phone)) $ticket->leader_phone = $request->leader_phone;
            // if (isset($request->guest_phone)) $ticket->guest_phone = $request->guest_phone;
            // if (isset($request->guest_email)) $ticket->guest_email = $request->guest_email;
            // if (isset($request->amount)) $ticket->amount = $request->amount;
            $ticket->save();
            $eventDetails->seatingOccupied = $occupiedSeats;
            $eventDetails->save();
            // return redirect()->back()->with('message', 'Ticket assigned!');

            return response()->json([
                'status' => 'success',
                'message' => 'Seat assigned successfully'
            ]);
        } 
        else 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat assignment failed'
            ], 400);
        }
    }

    public function GetassignTicket(Request $request)
    {
        $ticket = Tickets::where('ticketId', $request->ticketId)->first();
        $html = '';
        if ($ticket) {
            $html .= '<div class="form-group">
                        <label for="leader_name">Leader</label>
                        <input type="text" class="form-control" required name="leader_name" value="' . $ticket->holder_name . '" id="leader_name"  placeholder="Leader">
                    </div>
                    <div class="form-group">
                        <label for="leader_phone">Leader Phone</label>
                        <input type="text" class="form-control" required name="leader_phone" value="' . $ticket->leader_phone . '" id="leader_phone"  placeholder="Leader Phone">
                    </div>
                    <div class="form-group">
                        <label for="leader_email">Leader Email</label>
                        <input type="text" class="form-control" required name="leader_email" value="' . $ticket->holder_email . '" id="leader_email"  placeholder="Leader Email">
                    </div>
                    <div class="form-group">
                        <label for="guest_phone">Guest Phone</label>
                        <input type="text" class="form-control" required name="guest_phone" value="' . $ticket->guest_phone . '" id="guest_phone"  placeholder="Guest Phone">
                    </div>
                    <div class="form-group">
                        <label for="guest_email">Guest email</label>
                        <input type="text" class="form-control" required name="guest_email" value="' . $ticket->guest_email . '" id="guest_email"  placeholder="Guest email">
                    </div>

                    <div class="form-group">
                        <label for="table_num">Table number</label>
                        <input type="text" class="form-control" required readonly name="table_num" value="' . $ticket->seatNumber . '" id="table_num"  placeholder="Table number">
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount Paid</label>
                        <input type="number" class="form-control" required  name="amount" value="' . $ticket->amount . '" id="amount" placeholder="Amount Paid">
                    </div>
                    ';
        }
        return response()->json(['html' => $html]);
    }

    public function UnassignTicketSeatNumber(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $ticket = Tickets::where('seatNumber', $request->seatNumber)->where('eventId', $request->eventId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->first();
        $this->unassignTicketCore($ticket);
        
    }

    public function UnassignTicket(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $ticket = Tickets::where('ticketId', $request->ticketId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->first();
        $this->unassignTicketCore($ticket);
    }

    private function unassignTicketCore($ticket)
    {
        $seatNumber = "";
        $eventId = "";
        $tableNumber = "";
        if ($ticket)
        {
            $seatNumber = $ticket->seatNumber;

            if (preg_match('/T(\d+)S/', $seatNumber, $matches))
            {
                $tableNumber = (int) $matches[1]; // Extracted number
            }

            // $ticket->holder_name = '';
            // $ticket->holder_email = '';
            $ticket->seatNumber = '';
            $eventId = $ticket->eventId;
            $ticket->save();
            
            
            $userId = auth()->id();
            $userType = auth()->user()->userType;
            $eventDetails = EventDetails::where('eid', $eventId)->when($userType == 1, function ($query) use ($userId){
                return $query->where('userId', '=', $userId);
            })->first();
            $occupiedSeats = json_decode($eventDetails->seatingOccupied, true);

            if(!empty($tableNumber))
            {
                if (isset($occupiedSeats[$tableNumber])) {
                    // Find the index of the seat
                    $index = array_search($seatNumber, $occupiedSeats[$tableNumber]);

                    // If found, remove the seat
                    if ($index !== false)
                    {
                        unset($occupiedSeats[$tableNumber][$index]);
                        
                        // Reindex the array to avoid gaps in numeric keys
                        $occupiedSeats[$tableNumber] = array_values($occupiedSeats[$tableNumber]);
                        
                        $eventDetails->seatingOccupied = $occupiedSeats;
                        $eventDetails->save();
                    }
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Ticket Un-assigned successfully!']);
        }
        else
        {
            return response()->json(['status' => 'error', 'message' => 'Ticket not found.']);
        }
    }


    public function checkAssignValue(Request $request)
    {
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        $eventId = $request->eventId;
        $tableNumber = $request->tableNumber;

        $eventDetails = EventDetails::where('eid', $eventId)->when($userType == 1, function ($query) use ($userId) {
            return $query->where('userId', '=', $userId);
        })->first();

        $arrangement = $eventDetails->seatArrangement;
        $seatingPlan = json_decode($eventDetails->seatingplan, true);
        $allSeats = $allOccupied = $allAvailable = [];
        foreach($seatingPlan as $individualTable => $details)
        {
            $numberofseats = $details["capacity"];
            if($individualTable == $tableNumber)
            {
                if($arrangement == "roundTable")
                {
                    for($i = 1; $i <= $numberofseats; $i++)
                    {
                        $place = "T".$tableNumber."S".$i;
                        $allSeats[] = [
                            "place" => $place,
                            "seat" => "Seat " . $i
                        ];
                        // $allSeats[] = $place;
                    }
                }
                else
                {
                    for($i = 1; $i <= $numberofseats; $i++)
                    {
                        $place = "C".$tableNumber."S".$i;
                        $allSeats[$place] = [
                            "place" => $place,
                            "seat" => "Seat " . $i
                        ];
                        // $allSeats[] = $place;
                    }
                }
            }
        }
        $seatingOccupied = json_decode($eventDetails->seatingOccupied, true);

        if(!empty($seatingOccupied))
        {
            foreach($seatingOccupied as $individualTable => $seats)
            {
                if($individualTable == $tableNumber)
                {
                    $allOccupied = $seats;
                }
            }
        }
        $allAvailable = array_diff_key($allSeats, $allOccupied);

        return response()->json([
            'availableSeatNumbers' => array_values($allAvailable) // array_values to reindex the array
        ]);
    }


    public function generateTicketPdf(Request $request)
    {
        // ini_set('max_execution_time', 300);
        $userId = auth()->id();
        $ticket = Tickets::where('ticketId', $request->id)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->first();

        if ($ticket)
        {
            return view('pages.ticket', compact('ticket'));
        }

        // Create the PDF
        // $pdf = Pdf::loadView('pages.tickets.ticketDesign', $data)
        //            ->setPaper([0, 0, 10.63 * 72, 2.97 * 72], 'landscape'); // Set paper size in points
        // // Optionally, you can download the PDF file
        // return $pdf->stream('event_ticket.pdf');
    }

    public function generateTicketPdfSeatingPlan(Request $request)
    {
        $eventId = $request->eventId;
        $ticketId = $request->ticketId;
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        // ini_set('max_execution_time', 300);

        $ticket = Tickets::where('ticketId', $ticketId)->where('eventId', $eventId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->first();

        if ($ticket)
        {
            return view('pages.ticketSeatingPlan', compact('ticket'));
        }

        // Create the PDF
        // $pdf = Pdf::loadView('pages.tickets.ticketDesign', $data)
        //            ->setPaper([0, 0, 10.63 * 72, 2.97 * 72], 'landscape'); // Set paper size in points
        // // Optionally, you can download the PDF file
        // return $pdf->stream('event_ticket.pdf');
    }

    public function seatPlanning()
    {
        $userType = auth()->user()->userType;
        $userId = auth()->id();
        $allEvent = Event::when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->get();

        return view('pages.tickets.seatPlanning', compact('allEvent'));
    }

    private function tableAndChairSize($seatsPerTable)
    {
        $tableSize = 100; // Default size
        $seatSize = 30; // Default seat size

        if ($seatsPerTable > 5 && $seatsPerTable <= 10)
        {
            $tableSize = 130;
            $seatSize = 35;
        }
        if ($seatsPerTable > 10 && $seatsPerTable <= 20)
        {
            $tableSize = 200;
            $seatSize = 35;
        }
        elseif ($seatsPerTable > 20 && $seatsPerTable <= 30)
        {
            $tableSize = 200;
            $seatSize = 20;
        }
        elseif ($seatsPerTable > 30 && $seatsPerTable <= 40) {
            $tableSize = 250;
            $seatSize = 20;
        }
        elseif ($seatsPerTable > 50 && $seatsPerTable <= 80) {
            $tableSize = 600;
            $seatSize = 20;
        }

        return [
            "table" => $tableSize,
            "seat" => $seatSize
        ];
    }

    private function htmlGenerateGridPlan($details, $seatDetails, $tablenum, $eventId)
    {
        $totalSeats = $details->capacity;

        $seat = 0;
        $html = "";

        $html .= '<div class="seat-row">';
        $html .= '<div class="row-label">';

        // $html .= '<div class="table-info" data-table-num="' . $tablenum . '" onclick="showTableTooltip(event, ' . $tablenum . ', \'' . $details->tableType . '\', \'' . $details->name . '\')">';
        if (!empty($details->name))
        {
            $html .= '<a class="rowIdentifier" href="#" data-section-type="grid" onclick="showTableTooltip(event, ' . $tablenum . ', \'' . $details->tableType . '\', \'' . $details->name . '\', \'grid\')" data-type="row" data-id="Row' . $tablenum . '">' . $details->name . ':</a>';
        } 
        else
        {
            $html .= '<a class="rowIdentifier" href="#" data-section-type="grid" onclick="showTableTooltip(event, ' . $tablenum . ', \'' . $details->tableType . '\', \'' . $details->name . '\', \'grid\')" data-type="row" data-id="Row' . $tablenum . '">Row ' . $tablenum . ':</a>';
        }
        // $html .= '</div>';
        
        $html .= '</div>'; // Row label

        // echo "Total seats are $totalSeats";
        for ($j = 1; $j <= $totalSeats; $j++)
        {
            $seatNumber = 'C' . $tablenum . 'S' . $j; // Generate seat number in the format C1S1, C1S2, etc.
            $seat++;
            // $seatColor = isset($seatDetails[$seatNumber]) ? 'grey' : 'green';
            $seatColor = isset($seatDetails[$seatNumber]) ? '#dc3545' : '#17a2b8';

            if(isset($seatDetails[$seatNumber]["status"]))
            {
                if($seatDetails[$seatNumber]["status"] == -10)
                {
                    $seatColor = "#6c757d";
                }
            }
            // $seatAction = isset($seatDetails[$seatNumber]) ? 'unassignThisTicket' : 'assignThisTicket';
            $seatType = isset($seatDetails[$seatNumber]) ? 'unassignThisTicket' : 'assignThisTicket';

            if(isset($seatDetails[$seatNumber]["status"]) && $seatDetails[$seatNumber]["status"] == -10)
            {
                $tooltip = "Seat Number " . $seatDetails[$seatNumber]["seat_number"] . " is cancelled";
            }
            else
            {
                $tooltip = isset($seatDetails[$seatNumber])
                ? ('Holder Name: ' . $seatDetails[$seatNumber]['holder_name'] . ', Leader Name: ' . $seatDetails[$seatNumber]['leader_name']) . ', Ticket ID: ' . $seatDetails[$seatNumber]['ticket_id'] : "";
            }

            $html .= '<div class="seat-grid" data-seat-number="'.$seatNumber.'" data-ticket-type="'. $seatType .'" data-ticket-id="' . ($seatDetails[$seatNumber]['ticket_id'] ?? '') . '" data-table-num="'.$tablenum.'" data-event-id="'.$eventId.'" style="background-color: ' . $seatColor . ';" title="' . $tooltip . '">' . $j . '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function htmlGenerateTablePlan($details, $seatDetails, $tablenum, $eventId)
    {
        $totalSeats = $details->capacity;

        $html = "";
        $tableAndChairSizes = $this->tableAndChairSize($totalSeats);
        $radius = ($tableAndChairSizes["table"] / 2) + $tableAndChairSizes["seat"];
        // $radius = ($tableSize / 2) + $seatSize;

        $html .= '<div id="seating-plan">';
        $html .= '<div class="table-container" style="width: ' . ($tableAndChairSizes["table"] + 50) . 'px; height: ' . ($tableAndChairSizes["table"] + 50) . 'px; position: relative;">';
        $html .= '<div class="table-seatPlan" style="width: ' . $tableAndChairSizes["table"] . 'px; height: ' . $tableAndChairSizes["table"] . 'px; position: relative;">';

        if(!empty($details->name))
        {
            //$html .= '<a href="#" class="show-details" data-type="table" data-id="Table' . $tablenum . '">' . $details->name . '</a>';
            // $html .= '<div class="table-info text-center">';
            // $html .= '<strong>Table ' . $tablenum . '</strong><br>';  // Table Number
            // $html .= '<span>VIP Table</span><br>';  // Table Type (hardcoded)
            // $html .= '<span>' . $details->name . '</span>';  // Table Name
            // $html .= '</div>';

            // $html .= '<div class="table-info">';
            // $html .= '<strong>Table ' . $tablenum . '</strong><br>';  // Table Number
            // $html .= '<span>' . $details->tableType . '</span><br>';  // Table Type (hardcoded)
            // $html .= '<span>' . $details->name . '</span>';  // Table Name
            // $html .= '</div>';

            $html .= '<div class="table-info" data-section-type="table" data-table-num="' . $tablenum . '" onclick="showTableTooltip(event, ' . $tablenum . ', \'' . $details->tableType . '\', \'' . $details->name . '\', \'table\')">';
            $html .= '    <strong>Table ' . $tablenum . '</strong><br>';
            $html .= '    <span>' . $details->tableType . '</span><br>';
            $html .= '    <span>' . $details->name . '</span>';
            $html .= '</div>';
        }
        else
        {
            $html .= '<a href="#" data-section-type="table" class="show-details" data-type="table" data-id="Table' . $tablenum . '">Table ' . $tablenum . '</a>';
        }
        $html .= '</div>';

        for ($j = 0; $j < $totalSeats; $j++)
        {
            $seatNumber = 'T' . $tablenum . 'S' . ($j + 1); // Generate seat number in the format T1S1, T1S2, etc.
            $angle = (360 / $totalSeats) * $j; // Angle in degrees
            $x = $radius * cos(deg2rad($angle)); // Center seat calculation
            $y = $radius * sin(deg2rad($angle)); // Center seat calculation

            $seatColor = isset($seatDetails[$seatNumber]) ? '#dc3545' : '#17a2b8';

            if(isset($seatDetails[$seatNumber]["status"]))
            {
                if($seatDetails[$seatNumber]["status"] == -10)
                {
                    $seatColor = "#6c757d";
                }
            }
            $seatType = isset($seatDetails[$seatNumber]) ? 'unassignThisTicket' : 'assignThisTicket';
            // $seatAction = isset($seatDetails[$seatNumber]) ? 'seatTooltipBooked' : 'seatTooltipOpen';

            if(isset($seatDetails[$seatNumber]["status"]) && $seatDetails[$seatNumber]["status"] == -10)
            {
                $tooltip = "Seat Number " . $seatDetails[$seatNumber]["seat_number"] . " is cancelled";
            }
            else if (isset($seatDetails[$seatNumber]["status"]) && $seatDetails[$seatNumber]["status"] == 1)
            {
                $seatColor = "#28a745";
                $tooltip = "Seat Number " . $seatDetails[$seatNumber]["seat_number"] . " is scanned!";
            }
            else
            {
                $tooltip = isset($seatDetails[$seatNumber])
                    ? 'Name: ' . $seatDetails[$seatNumber]['holder_name'] . ', Leader Name: ' . $seatDetails[$seatNumber]['leader_name'] . ', Ticket ID: ' . $seatDetails[$seatNumber]['ticket_id']
                    : 'Available';
            }

            $html .= '<div class="seat" data-seat-number="'.$seatNumber.'" data-ticket-type="'. $seatType .'" data-ticket-id="' . ($seatDetails[$seatNumber]['ticket_id'] ?? '') . '" data-table-num="'.$tablenum.'" data-event-id="'.$eventId.'" style="width: ' . $tableAndChairSizes["seat"] . 'px; height: ' . $tableAndChairSizes["seat"] . 'px; top: ' . ($tableAndChairSizes["table"] / 2 + $y) . 'px; left: ' . ($tableAndChairSizes["table"] / 2 + $x) . 'px; background-color: ' . $seatColor . ';" title="' . $tooltip . '">' . ($j + 1) . '</div>';

        }

        $html .= '</div>';

        return $html;
    }


    public function generateSeatingPlan(Request $request)
    {
        $eventId = $request->eventId;
        $userId = auth()->id();
        $userType = auth()->user()->userType;
        if(empty($eventId))
        {
            return response()->json(['html' => 'Event is missing, select from dropdown']);
        }
        $assignEvent = EventDetails::where('eid', $request->eventId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->first();
        if(!empty($assignEvent))
        {
            $seatingPlanTxt = $assignEvent->seatingplan;
            $seatingPlan = json_decode($seatingPlanTxt);
        }
        else
        {
            return response()->json(['html' => 'No seating arrangement found!']);
        }
        // Retrieve existing seat details for the event
        
        $assignedSeats = DB::table('tickets')
            ->where('eventId', $request->eventId)
            ->when($userType == 1, function ($query) use ($userId){
                return $query->where('userId', '=', $userId);
            })
            ->get(['seatNumber', 'holder_name', 'holder_email', 'tickettype_name', 'price', 'leader_name', 'ticketId', 'scaned']);

            // $userId = auth()->id();
            // dd("User ID:", $userId); // Debugging

            // $assignedSeats = DB::table('tickets')
            //     ->where('eventId', $request->eventId)
            //     ->where('userId', $userId)
            //     ->get();

            // dd($assignedSeats); // Check output

        // if(!empty($assignedSeats))
        // {
        //     foreach($assignedSeats as $seat)
        //     {
        //         echo $seat->ticketId . " </br>";
        //     }
        // }
        // else
        // {
        //     echo "ahmad tickets not found";
        // }
        // die;

        // Create an array to map seat numbers to holder details
        $seatDetails = [];
        foreach ($assignedSeats as $seat)
        {
            $seatDetails[$seat->seatNumber] = [
                'holder_name' => $seat->holder_name,
                'leader_name' => $seat->leader_name,
                'ticket_id' => $seat->ticketId,
                'seat_number' => $seat->seatNumber,
                'status' => $seat->scaned
            ];
        }

        $htmlTicketTypes = "";
        $ticketTypes = EventTicketType::where('event_id', $request->eventId)->when($userType == 1, function ($query) use ($userId){
            return $query->where('userId', '=', $userId);
        })->get();

        $htmlTicketTypes .= '<label for="tableType">Table Type</label>';
        $htmlTicketTypes .= '<select name="tableType[]" class="form-control tableTypeSelect"">';
        $htmlTicketTypes .= '<option value="">Select table type</option>';
        foreach($ticketTypes as $ticket)
        {
            $htmlTicketTypes .= '<option value="'.$ticket->ticketType.'">'.$ticket->ticketType.'</option>';
        }
        $htmlTicketTypes .= '</select>';
        


        $html = "";
        if(!empty($seatingPlan))
        {
            foreach($seatingPlan as $table => $details)
            {
                // var_dump($details);
                if ($assignEvent->seatArrangement == 'grid')
                {
                    $html .= '<div class="table-container-grid">';
                    $html .= $this->htmlGenerateGridPlan($details, $seatDetails, $table, $eventId);
                    $html.= "</div>";
                }
                else
                {
                    $html .= $this->htmlGenerateTablePlan($details, $seatDetails, $table, $eventId);
                    $html .= '</div>';
                }
            }
        }
        else
        {
            return response()->json(['html' => 'No Seating Arrangement found!']);
        }

        $html .= '<div id="seatTooltip" class="seat-tooltip p-3">';
        $html .= '    <div class="tooltip-header d-flex justify-content-between align-items-center">';
        $html .= '        <h5 class="tooltip-title mb-0">Seat Actions</h5>';
        $html .= '        <button id="closeTooltip" class="btn-close btn-close-red" aria-label="Close"><i class="fas fa-close"></i></button>';
        $html .= '    </div>';
        $html .= '    <hr class="my-2">';
        $html .= '    <p id="seatInfo" class="mb-2 text-muted"></p>';  // Seat details
        $html .= '    <div class="payment-status mb-2">';
        $html .= '        <span id="paymentStatus" class="badge bg-success">Paid</span>';
        $html .= '    </div>';
        $html .= '    <div class="d-flex flex-column gap-2">';
        $html .= '        <button id="assignSeatToolTip" style="display: none;" class="btn btn-success btn-sm assignThisTicket">Assign Ticket</button>';
        $html .= '        <button id="viewTicketToolTip" style="display: none;" class="btn btn-primary btn-sm viewTicket">View Ticket</button>';
        $html .= '        <button id="unassignSeatToolTip" style="display: none;" class="btn btn-warning btn-sm unassignThisTicket">Unassign Ticket</button>';
        $html .= '        <button id="cancelTicketToolTip" style="display: none;" class="btn btn-danger btn-sm cancelThisTicket">Cancel Ticket</button>';
        $html .= '    </div>';
        $html .= '</div>';

        $html .= '<div id="tableTooltip" class="table-tooltip p-3" style="display: none; position: absolute;">';
        $html .= '    <div class="tooltip-header d-flex justify-content-between align-items-center">';
        $html .= '        <h5 class="tooltip-title mb-0">Section Actions</h5>';
        $html .= '        <button id="closeTableTooltip" class="btn-table-close btn-table-close-red" aria-label="Close"><i class="fas fa-close"></i></button>';
        $html .= '    </div>';
        $html .= '    <hr class="my-2">';
        $html .= '    <p id="tableInfo" class="mb-2 text-muted">Section Details</p>'; // Table details placeholder
        $html .= '    <div class="d-flex flex-column gap-2">';
        $html .= '        <input type="hidden" class="hiddenTableNum" value="" />';
        $html .= '        <input type="hidden" class="hiddenEventId" value="'.$eventId.'" />';
        $html .= '        <button id="viewSectionDetails" class="btn btn-danger btn-sm show-details">View Section Details</button>';
        $html .= '        <button id="deleteTableToolTip" class="btn btn-danger btn-sm deleteTable">Delete Section</button>';
        $html .= '    </div>';
        $html .= '</div>';


        // Collapsible section for all rows or tables
        $html .= '<div id="collapseSection" class="collapse">';
        $html .= '<div id="collapseContent" class="card card-body">';
        $html .= '<table class="table table-bordered">';
        $html .= '<thead><tr><th>Seat Number</th><th>Holder Name</th><th>Holder Email</th></tr></thead>';
        $html .= '<tbody id="detailsBody"></tbody>';
        $html .= '</table>';
        $html .= '</div></div>';

        //event ticket types
        return response()->json(['html' => $html,'eventDetails' => $assignEvent, 'tableTypes' => $htmlTicketTypes]);
    }

    public function saveTableAndSeats(Request $request)
    {
        $tablenumber = $request->tablenum;
        $tablename = $request->tablename;
        $tableseats = $request->tableseats;
        $eventId = $request->eventId;
        $tablettype = $request->tabletype;

        $directory = "qrcodes/event_{$eventId}";

        if (!Storage::disk('public')->exists($directory)) 
        {
            Storage::disk('public')->makeDirectory($directory);
        }

        if(empty($tablenumber) || empty($tableseats))
        {
            echo "Table number or Number of seats cannot be empty";
            exit();
        }

        //select ticket number from database and if not found make it zero
        $ticket = Tickets::where('eventId', $eventId)->orderBy('ticketId', 'desc')->first();
        if($ticket)
        {
            $ticketNumber = $ticket->ticketNumber;
        }
        else
        {
            $ticketNumber = 0;
        }
        
        
        $eventDetails = EventDetails::where('eid', $eventId)->first();
        $userId = $eventDetails->userId;
        $seatingPlanTxt = $eventDetails->seatingplan;
        $seatingPlan = json_decode($seatingPlanTxt, true);
        
        if (!isset($seatingPlan[$tablenumber]))
        {
            $seatingPlan[$tablenumber] = [
                "name" => $tablename,
                "number" => $tablenumber,
                "capacity" => $tableseats,
                "tableType" => $tablettype
            ];

            for($i = 1; $i <= $tableseats; $i++)
            {
                // $currentSeatNumber = $uptoSeatNumbers + $i;
                // $place = $sectionType . $tableNumber . "S" . $currentSeatNumber;

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
                $ticket->tickettype_name = $seatingPlan[$tablenumber]["tableType"];
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

            // $seatingPlan[$tablenumber] = $tableseats;
            $eventDetails->seatingplan = json_encode($seatingPlan);
            $eventDetails->save();

            $message = "";
            if(isset($tablename))
            {
                $message = " name ($tablename)";
            }

            echo 'Table ID ' . $tablenumber . $message . ' added with capacity ' . $tableseats;
        }
        else
        {
            echo "Sorry! table name/number already exists";
        }
        
    }

    public function cancelTicketSeatNumber(Request $request)
    {
        $eventId = $request->eventId;
        $ticketId = $request->ticketId;
        $ticket = Tickets::where('ticketId', $ticketId)->where('eventId', $eventId)->first();
        if($ticket)
        {
            $ticket->scaned = -10;
            $ticket->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Ticket cancelled successfully!'
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 400);   
        }
        die;

    }

    public function ticketTypePage()
    {
        $TicketTypes = TicketType::get();
        return view('pages.tickets.ticketType', compact('TicketTypes'));
    }

    public function ticketType(Request $request)
    {
        $createTicketType = new TicketType;
        $createTicketType->ticketType = $request->ticketType;
        $createTicketType->save();
        return redirect()->back()->with('message', "Ticket Type is Added SuccessFully");
    }

    public function UpdateTicketType(Request $request)
    {
        $TicketType = TicketType::find($request->id);
        $TicketType->ticketType = $request->ticketType;
        $TicketType->save();
        return redirect()->back()->with('message', "Ticket Updated SuccessFully");
    }

    public function DeleteticketType(Request $request)
    {
        $TicketType = TicketType::find($request->id);
        if ($TicketType) {
            $TicketType->delete();
        }
        return redirect()->back()->with('message', "Ticket Deleted SuccessFully");
    }

    public function scanner()
    {
        return view('scanner');
    }



    public function ticketscan(Request $request)
    {
        // $ticket->ticketId . ' | ' . $ticket->seatNumber . ' | ' . $ticket->holder_email . ' | ' . $ticket->holder_name;
        $res = 0;
        $msg = '';

        $details = explode(" | ",$request->result);
        $data = explode(' | ', $request->result);
        if (count($data) == 4)
        {
            $ticket = Tickets::where([
                'ticketId' => $data[0] ?? '',
                'seatNumber' => $data[1] ?? ''
            ])->first();

            if ($ticket && $ticket->scaned == 0)
            {
                $ticket->scaned = 1;
                $ticket->scaned_at = date('d-m-Y h:i:s A');
                $ticket->save();
                $res = 1;
            }
            elseif ($ticket && $ticket->scaned == 1)
            {
                $res = 2;
                $msg = ' on ' . $ticket->scaned_at;
            }
        }

        return response()->json(['ticket' => $res, 'msg' => $msg]);
    }
}
