<div style="border: 2px solid #ddd; max-width: 380px; margin: auto; border-radius: 12px; padding: 16px; background: #f9f9f9; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); font-family: Arial, sans-serif;">
    <!-- Event Title & Date -->
    <h3 style="text-align: center; color: #333; margin-bottom: 5px;">{{ $ticket->event->title }}</h3>
    <h5 style="text-align: center; color: #777; margin-top: 0px;">{{ $ticket->event->eventDate }}</h5>
    
    <!-- QR Code -->
    <div class="qr"></div>
    <div style="display: flex; justify-content: center; margin: 10px 0;">
        <?php
            $qrCodeData = $ticket->ticketId . ' | ' . $ticket->seatNumber . ' | ' . $ticket->holder_email . ' | ' . $ticket->holder_name;
            echo \DNS2D::getBarcodeHTML($qrCodeData, 'QRCODE', 6, 5);
        ?>
    </div>

    <!-- Ticket Information -->
    <table style="width: 100%; font-size: 14px; margin-top: 10px;">
        <tbody>
            <tr><td style="font-weight: bold;">Leader:</td><td>{{ $ticket->holder_name }}</td></tr>
            <tr><td style="font-weight: bold;">Email:</td><td>{{ $ticket->holder_email }}</td></tr>
            <tr><td style="font-weight: bold; color: #e74c3c;">Seat:</td><td style="color: #e74c3c;">{{ $ticket->seatNumber }}</td></tr>
            <tr><td style="font-weight: bold;">Guest Email:</td><td>{{ $ticket->guest_email }}</td></tr>
            <tr><td style="font-weight: bold;">Guest Phone:</td><td>{{ $ticket->guest_phone }}</td></tr>
        </tbody>
    </table>

    <!-- Event Details -->
    <h4 style="margin-top: 15px; text-align: center; color: #333;">Event Details</h4>
    <hr style="border: 0.5px solid #ddd; width: 80%; margin: auto;">
    <table style="width: 100%; font-size: 14px;">
        <tbody>
            <tr><td style="font-weight: bold;">Start Date:</td><td>{{ $ticket->event->eventDate }}</td></tr>
            <tr><td style="font-weight: bold;">End Date:</td><td>{{ $ticket->event->endDate }}</td></tr>
            <tr><td style="font-weight: bold;">Address:</td><td>{{ $ticket->event->address }}</td></tr>
        </tbody>
    </table>

    <!-- Decorative Strip at Bottom -->
    <div style="width: 90%; background: #e74c3c; height: 35px; border-radius: 16px; margin: 20px auto; position: relative;">
        <div style="background: white; height: 59px; width: 49px; transform: rotate(30deg); border-radius: 45px 22px 0px 0px; position: absolute; right: 10px; top: -12px;"></div>
    </div>
</div>
