<html><head>
	<title></title>
</head>
<body style="font-family: sans-serif;" >
	<div style="border: 1px solid grey;max-width: 350px;margin: auto;border-radius: 10px;padding: 6px;">
    <h3 style="text-align: center;">{{ $ticket->event->title.' '.$ticket->event->eventDate }}</h3>
<div class="qr"></div>
<div class="container" style="max-width: 320px;margin: auto;">
    <div style="justify-content: center;display: flex;" >
        <?php
        $qrCodeData = $ticket->ticketId . ' | ' . $ticket->seatNumber . ' | ' . $ticket->holder_email . ' | ' . $ticket->holder_name;
        echo \DNS2D::getBarcodeHTML($qrCodeData,'QRCODE',6,5);
        ?>
    </div>
    <table style="margin-top: 10px;" >
        <tbody>
        <tr>
            <td><b>Leader:</b></td>
            <td>{{ $ticket->holder_name }}</td>
        </tr>
        <tr>
            <td><b>Email:</b></td>
            <td>{{ $ticket->holder_email }}</td>
        </tr>
        <tr>
            <td><b>Seat:</b></td>
            <td>{{ $ticket->seatNumber }}</td>
        </tr>
        
        <tr>
            <td><b>Guest Email:</b></td>
            <td>{{ $ticket->guest_phone }}</td>
        </tr>
        <tr>
            <td><b>Guest Phone:</b></td>
            <td>{{ $ticket->guest_email }}</td>
        </tr>
       

        
        
        
        
        
        
        
    </tbody></table>
    <h4 style="margin-bottom: 0px;" >Event Details:</h4>
    <hr>
    <table>
        <tbody><tr>
            <td><b>Start Date:</b></td>
            <td>{{ $ticket->event->eventDate }}</td>
        </tr>
        <tr>
            <td><b>End Date:</b></td>
            <td>{{ $ticket->event->endDate }}</td>
        </tr>
        <!-- <tr>
            <td><b>Venue:</b></td>
            <td>12/12/18</td>
        </tr> -->
        <tr>
            <td><b>Address:</b></td>
            <td>{{ $ticket->event->address }}</td>
        </tr>
    </tbody></table>
</div>
<div style="width: 300px;background: red;height: 35px;border-radius: 16px;margin-top: 20px;margin-bottom: 21px;margin-left: auto;margin-right: auto;">
    <div style="background: white;height: 59px;width: 49px;transform: rotate(30deg);border-radius: 45px 22px 0px 0px;position: relative;right: -83%;top: -6px;"></div>
</div>
    
</div>
</body></html>