<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket</title>
    <style>
        @page {
            size: 10.63in 2.97in landscape; /* Width x Height */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            /* background: #f0f0f0; */
            background: url('img/ticketBg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color:white;
        }

        /* .ticket {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #333;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        } */
        .ticket{
            display: flex!;
        }

        .ticket-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px;
            /* background-color: #007bff; */
            color: white;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .ticket-header h1 {
            font-size: 15pt;
            margin: 0;
        }

        .ticket-header p {
            font-size: 8pt;
            margin: 2px 0;
        }

        .ticket-body {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
        }

        .ticket-details {
            flex: 1;
            padding: 5px;
            /* background: #f8f9fa; */
            border-radius: 5px;
        }

        .ticket-details p {
            margin: 2px 0;
        }

        .ticket-qr {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 0 0 30%;
        }

        .ticket-qr img {
            width: 80px;
            height: 80px;
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 5px;
        }

        
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>Event Title</h1>
            <p>Date: {{ $event_date }} | Time: {{ $event_time }}</p>
            <p>Venue: {{ $venue_name }}</p>
        </div>
        <div class="ticket-body">
            <div class="ticket-details">
                <p><strong>Ticket ID:</strong> {{ $ticket_id }}</p>
                <p><strong>Attendee:</strong> {{ $attendee_name }}</p>
                <p><strong>Seat:</strong> {{ $seat_number }}</p>
                <p><strong>Section:</strong> {{ $section }}</p>
            </div>
            <div class="ticket-qr">
                <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code">
            </div>
        </div>
      
    </div>
</body>
</html>
