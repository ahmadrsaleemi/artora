<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Smart Scan Ticket</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">

    <!--external css-->
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="{{asset('assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')}}" rel="stylesheet" />
    <!--right slidebar-->
    <link href="{{asset('css/slidebars.css')}}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <!-- JavaScript Libraries -->
    <!-- <script src="{{ asset('js/jquery.js') }}"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" ></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/fullcalendar/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('js/respond.min.js') }}"></script>
    <script src="{{ asset('js/slidebars.min.js') }}"></script>
    <script src="{{ asset('js/common-scripts.js') }}"></script>

    <!-- External Libraries -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <style>
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-item i {
            margin-right: 8px;
        }

        .seat-tooltip {
            display: none; /* Initially hidden */
            position: absolute;
            background: white;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .table-info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(255, 255, 255, 0.8); /* Light background to improve readability */
            border-radius: 50%; /* Keep it circular */
            width: 80%; /* Adjust based on table size */
            padding: 5px;
            font-size: 14px;
        }

        .seat-tooltip p {
            margin: 0;
            font-size: 14px;
        }

        .btn-close-red {
            filter: invert(38%) sepia(79%) saturate(3616%) hue-rotate(345deg) brightness(90%) contrast(107%);
            opacity: 1;
        }
        .btn-close-red:hover {
            filter: invert(20%) sepia(96%) saturate(6000%) hue-rotate(360deg) brightness(85%) contrast(120%);
        }

        #ticketTable {
            width: 100%; /* Make the table take full width */
            table-layout: fixed; /* Ensures columns don't auto-expand */
        }

        #ticketTable th, #ticketTable td {
            white-space: nowrap; /* Prevents wrapping */
            overflow: hidden; 
            text-overflow: ellipsis; /* Adds "..." if content is too long */
        }

        #ticketTable th:nth-child(1), 
        #ticketTable td:nth-child(1) { width: 10%; } /* Ticket ID */
        #ticketTable th:nth-child(2), 
        #ticketTable td:nth-child(2) { width: 15%; } /* Ticket Type */
        #ticketTable th:nth-child(3), 
        #ticketTable td:nth-child(3) { width: 20%; } /* Holder Name */
        #ticketTable th:nth-child(4), 
        #ticketTable td:nth-child(4) { width: 20%; } /* Leader Name */
        #ticketTable th:nth-child(5), 
        #ticketTable td:nth-child(5) { width: 10%; } /* Table */
        #ticketTable th:nth-child(6), 
        #ticketTable td:nth-child(6) { width: 10%; } /* Seats */
        #ticketTable th:nth-child(7), 
        #ticketTable td:nth-child(7) { width: 15%; } /* Actions */


        .table-tooltip {
            background: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            position: absolute;
            z-index: 1000;
            width: 250px;
            border: 1px solid #ddd;
        }

        .btn-table-close-red {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #dc3545;
        }

        .btn-table-close-red:hover {
            color: #b02a37;
        }

        #tableInfo {
            font-size: 14px;
            padding: 10px;
            color: #555;
        }

        .d-flex.gap-2 {
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .deleteTable {
            background-color: #dc3545;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            color: white;
            transition: background 0.3s ease-in-out;
        }

        .deleteTable:hover {
            background-color: #b02a37;
        }

        .btn-close-red {
            display: block;  /* Ensures visibility */
            background: none;
            border: none;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            color: #dc3545;  /* Red color */
            padding: 5px;
        }

        .btn-close-red:hover {
            color: #b02a37;  /* Darker red on hover */
        }
        .gallery-item {
            width: 130px;
            cursor: move;
            border: 2px solid #ddd;
            border-radius: 6px;
            padding: 4px;
            background: white;
        }
        .gallery-item.dragging {
            opacity: 0.5;
        }
        .gallery-item img {
            width: 100%;
            border-radius: 4px;
        }
        .remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border-radius: 50%;
            border: none;
            width: 22px;
            height: 22px;
            font-size: 12px;
        }
    </style>
  </head>

  <body>

    @include('layout.navbar')
    @include('layout.sideBar')

    @yield('content')


    <script>
        // new DataTable('#datatable-simple');
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#updateUserBtn').click(function() {
                $.ajax({
                    url: '{{ url("updateUser") }}',
                    type: 'POST',
                    data: $('#editUserForm').serialize(),
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            alert('User updated successfully');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to update user');
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert('An error occurred');
                    }
                });
            });
        });
        function rgbToHex(rgb) {
            var result = rgb.match(/\d+/g);
            return result
                ? "#" +
                    ((1 << 24) | (parseInt(result[0]) << 16) | (parseInt(result[1]) << 8) | parseInt(result[2]))
                        .toString(16)
                        .slice(1)
                : rgb;
        }

        document.addEventListener("DOMContentLoaded", function() {

            $('#updateEventDetailBtn').click(function() {
                $.ajax({
                    url: '{{ url("eventDetailsUpdate") }}',
                    type: 'POST',
                    data: $('#editEventDetailsForm').serialize(),
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            alert('Event Details updated successfully');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to update Event Details');
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert('An error occurred');
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            $('#updateEventBtn').click(function() {
                $.ajax({
                    url: '{{ url("editEvent") }}',
                    type: 'POST',
                    data: $('#editEventForm').serialize(),
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            alert('Event updated successfully');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to update Event!');
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert('An error occurred');
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Use event delegation to handle clicks on the Edit buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Retrieve data attributes from the clicked button
                    const id = this.getAttribute('data-id');
                    const firstName = this.getAttribute('data-firstname');
                    const lastName = this.getAttribute('data-lastname');
                    const email = this.getAttribute('data-email');
                    const userType = this.getAttribute('data-usertype');

                    // Set the values in the modal form
                    document.getElementById('user-id').value = id;
                    document.getElementById('userFirstName').value = firstName;
                    document.getElementById('userLastName').value = lastName;
                    document.getElementById('userEmail').value = email;
                    // document.getElementById('userType').value = userType;
                    document.getElementById('userTypeReadOnly').value = userType; // Optional, if you want to show the user type as read-only
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            $('.eventDetailEdit').click(function() {
                var eventId = $(this).data('id');
                var numberOfTickets = $(this).data('numberoftickets');
                var ticketPrice = $(this).data('ticketprice');
                var eventType = $(this).data('eventtype');
                var seatArrangement = $(this).data('seatarrangement');
                var ticketDivision = $(this).data('ticketdivision');

                $('#event-id').val(eventId);
                // console.log("Number of Tickets: " + numberOfTickets);

                $('#noOfTicket').val(numberOfTickets);
                $('#eventTicketPrice').val(ticketPrice);
                $('#eventType').val(eventType);
                $('#eventSeatArrangement').val(seatArrangement);
                $('#eventTicketDivision').val(ticketDivision);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            $('.eventEdit').click(function() {
                var eventId = $(this).data('eid');
                var title = $(this).data('title');
                var address = $(this).data('address');
                var contactnumber = $(this).data('contactnumber');
                var contactemail = $(this).data('contactemail');
                var description = $(this).data('description');

                $('#event-id').val(eventId);
                // console.log("Number of Tickets: " + numberOfTickets);

                $('#eventTitle').val(title);
                $('#eventDescription').val(description);
                $('#eventAddress').val(address);
                $('#eventContactNumber').val(contactnumber);
                $('#eventContactEmail').val(contactemail);
            });
        });


            function calculateSeats(){
                let numberOfTickets=0;// document.getElementById("numberOfTickets").value;
                let seatArrange=document.getElementById("seatArrange").value;
                let ticketDivision=document.getElementById("ticketDivision").value;
                let resultDiv=document.getElementById("resultDiv");
                let calculation=0;

                $("[name=\"typeType[quantity][]\"]").each((a,b)=>{
                    let qty = b.value;
                    if (!isNaN(parseInt(qty))) {
                       numberOfTickets += parseInt(qty);
                    }
                    })

                $("[name=\"physicalTicket[quantity][]\"]").each((a,b)=>{
                    let qty = b.value;
                    if (!isNaN(parseInt(qty))) {
                       numberOfTickets += parseInt(qty);
                    }
                    })

                if(isNaN(parseInt(ticketDivision))){
                    ticketDivision=1;
                }

                if(numberOfTickets!="" || seatArrange!=""){
                        if(ticketDivision<=0){
                        calculation=0;
                    }else{
                        calculation=parseInt(numberOfTickets)/parseInt(ticketDivision);
                    }


                    // if(seatArrange=="grid"){
                    // resultDiv.innerHTML=`<div>

                    //     <p class='mb-0'>sSeats per row: ${Math.ceil(calculation)}</p>
                    //     <p class='mb-0'>Columns: ${ticketDivision}</p>
                    //     <p>Total Tickets: ${numberOfTickets}</p>
                    //     </div>`;

                    // }else{
                    let type ='Tables';
                    if(seatArrange=="grid"){
                        type ='Rows';
                    }

                    resultDiv.innerHTML=`<div>`;
                        // <p class='mb-0'>sSeats per table: ${Math.ceil(calculation)}</p>
                        // <p class='mb-0'>Tables: ${ticketDivision}</p>
                        // <p>Total Tickets: ${numberOfTickets}</p>
                        // </div>`;
                    // }

                    $('.ticketTypes .input-group.mb-2').each((a,b)=>{
                        if ($(b).find('option:selected').val() != '') {
                            let _qty = $(b).find('input').eq(0).val();
                            resultDiv.innerHTML += `<p class='mb-0'>Total Digital <b>`+$(b).find('option:selected').text()+` tickets</b> : <b>${Math.ceil(_qty)}</b></p>`;
                        }
                    });

                    $('.physicalticketTypes .input-group.mb-2').each((a,b)=>{
                        if ($(b).find('option:selected').val() != '') {
                            let _qty = $(b).find('input').eq(0).val();
                            resultDiv.innerHTML += `<p class='mb-0'>Total Physical <b>`+$(b).find('option:selected').text()+` tickets</b> : <b>${Math.ceil(_qty)}</b></p>`;
                        }
                    });

                    resultDiv.innerHTML += `
                        <p>Total Number of Tickets / Capacity: <b>${numberOfTickets}</b></p>
                        </div>`;
                }
            }

            // calculateSeats();


        // document.addEventListener("DOMContentLoaded", function() {

        function generateTicket(event) {
            event.preventDefault();
            let eventId = $("#eventId").val();
            let responseDiv = $("#generateTicketResponse");
            let generateButton = $(".btnGenerate");

            // Change cursor to "loading"
            $("body").css("cursor", "progress");

            // Display loading message with spinner
            responseDiv.removeClass("alert-success alert-danger d-none")
                    .addClass("alert alert-info")
                    .html('<span class="spinner-border spinner-border-sm"></span> Generating tickets... This may take a few minutes.');

            // Disable button & change text
            generateButton.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Generating...');

            $.ajax({
                url: "{{route('generateTicket')}}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",  
                    eventId: eventId,
                },
                success: function(response) {
                    if (response === "success") {
                        responseDiv.removeClass("alert-info").addClass("alert-success")
                                .html("✅ Tickets generated successfully!");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        responseDiv.removeClass("alert-info").addClass("alert-danger")
                                .html("❌ Ticket generation failed. Please try again.");
                        generateButton.prop("disabled", false).html("Generate");
                    }
                },
                error: function(xhr) {
                    responseDiv.removeClass("alert-info").addClass("alert-danger")
                            .html("⚠️ An error occurred: " + xhr.responseText);
                    generateButton.prop("disabled", false).html("Generate");
                },
                complete: function() {
                    // Reset cursor to default
                    $("body").css("cursor", "default");
                }
            });
        }


        // });
        document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById("eventSelect")){

            document.getElementById("eventSelect").addEventListener("change", function() {
                loadTickets(this.value);
            });
            loadTickets(document.getElementById("eventSelect").value);
        }
        });

        // document.addEventListener("DOMContentLoaded", function() {
            let selectEventTicketsdatatable=null;
            function loadTickets(eventId, page = 1) {

                if (selectEventTicketsdatatable != null){
                    selectEventTicketsdatatable.destroy()
                }
                $.ajax({
                    url: "{{ url('selectEventTickets') }}",
                    method: "POST",
                    data: {
                        eventId: eventId,
                        page: page, // pass the current page number
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        $("#ticketBody").html(response.html);
                        $(".pagination-wrapper").html(response.pagination);

                        // Attach click event to pagination links
                        $(".pagination-wrapper a").click(function(e) {
                            e.preventDefault();
                            let page = $(this).attr('href').split('page=')[1];
                            loadTickets(eventId, page);
                        });
                        selectEventTicketsdatatable = new DataTable('#datatable-ajax');
                    },
                    error: function(xhr, error) {
                        alert("Response Error: " + xhr.responseText);
                    }
                });
            }
        // });
        // document.addEventListener("DOMContentLoaded", function() {
            function getAllTickets(id){
                let eventId = id.value;
                let url = "/ticket/Seat-Plan?id=" + eventId;
                $.ajax({
                    url: "{{url('getEventTickets')}}",
                    method: "POST",
                    data: {
                        eventId: eventId,
                        _token: "{{csrf_token()}}",
                    },
                    success: function(response) {
                        // Assuming you have a table with an id 'ticketTable' to append rows to

                        $('#ticketTable tbody').html(response.html);
                        if(response.html !== "")
                        {
                            $(".assignTicketViewSeatingPlan").attr("href", url);
                            $(".assignTicketViewSeatingPlan").css("display", "block");
                        }
                    },
                    error: function(xhr, error) {
                        alert("Error: " + xhr.responseText);
                    }
                });

            }
        // });
        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById("eventSelectAssign")){
                document.getElementById("eventSelectAssign").addEventListener("change", function() {
                    loadSeatingPlan(this.value);
                });
                let params = new URLSearchParams(window.location.search);
                let eventId = params.get('id');
                loadSeatingPlan(eventId);
            }
        });
        
        function loadSeatingPlan(eventId)
        {
            $('#eventId').val(eventId);
            $("#eventId1").val(eventId);
            $("#eventId2").val(eventId);
            $.ajax({
                url:"{{url('generateSeatingPlan')}}",
                method: "POST",
                data:{
                    eventId: eventId,
                    _token: "{{csrf_token()}}",
                },
                success: function(response){
                    if(response.html !== "Event is missing, select from dropdown")
                    {
                        $('#seating-plan').html(response.html);
                        $('.ticketTypeDropdown').html(response.tableTypes);
                        $(".btnAddTable").css("display", "block");
                        $("#seatingDropdown").css("display", "block");
                        let seatingplan = JSON.parse(response.eventDetails.seatingplan);
                        
                        let dropdown = $("#seatingSection");
                        dropdown.empty();
                        dropdown.append('<option value="">Select a Seating Section</option>');

                        $.each(seatingplan, function (key, value){
                            dropdown.append('<option data-total-seats="'+value.capacity+'" value="' + key + '">Table ' + key + '</option>');
                        })

                        $("#eventId1").val(eventId);
                        $("#eventId2").val(eventId);
                    }
                },
                error:function(xhr,error){
                    alert("Error: "+xhr.responseText);
                }
            });
        }

        $(document).on('change', '#seatingSection', function() {
            var totalSeats = $(this).find(':selected').data('total-seats');
            $("#currentSeats").val(totalSeats);
            $("#currentSeats").css("display","block");
            $("#lblCurrentSeats").css("display","block");
        });

        $(document).on('input', '#numSeats', function() {
            var addSeats = parseInt($(this).val()) || 0;
            var currentSeats = parseInt($("#currentSeats").val()) || 0;
            // $("#totalSeats").val(totalSeats);

            var totalSeats = currentSeats + addSeats;

            $("#totalSeats").val(totalSeats).css("display", "block");
            $("#lblTotalSeats").css("display", "block");
            // $("#totalSeats").css("display","block");
            // $("#lblTotalSeats").css("display","block");
        });

        function btnCancelAddSeatsModal()
        {
            $(".getNewSeats").val("");
            $("#seatingSection").prop('selectedIndex', 0);
            $("#btnSaveSeats").prop("disabled", false);
            $("#currentSeats").val("");
            $("#totalSeats").val("");

            $("#totalSeats").val(totalSeats).css("display", "none");
            $("#lblTotalSeats").css("display", "none");
            
            $("#currentSeats").css("display","none");
            $("#lblCurrentSeats").css("display","none");
        }

        function addSeatstoTable()
        {
            $("#btnSaveSeats").prop("disabled", true);
            let eventId = $("#eventId1").val();
            let updateSeats = $("#totalSeats").val();
            let newSeats = $(".getNewSeats").val();
            let tableNumber = $("#seatingSection").find(':selected').val();
            
            var formData = new FormData();
            formData.append("eventId", eventId);
            formData.append("updateSeats", updateSeats);
            formData.append("tableNumber", tableNumber);
            formData.append("newSeats", newSeats);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url: "{{url('/event/AddSeatsToTable')}}",  // Update with your route
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == "success") {
                        $("#addMoreSeatsResponse").html(response.message);
                        $("#addMoreSeatsResponse").css({
                            "background-color": "#d4edda",  // Light green background
                            "color": "#155724",  // Dark green text
                            "border": "1px solid #c3e6cb", // Green border
                            "padding": "10px",
                            "border-radius": "5px"
                        });
                    } else {
                        $("#addMoreSeatsResponse").html(response.message);
                        $("#addMoreSeatsResponse").css({
                            "background-color": "#f8d7da",  // Light red background
                            "color": "#721c24",  // Dark red text
                            "border": "1px solid #f5c6cb", // Red border
                            "padding": "10px",
                            "border-radius": "5px"
                        });
                    }
                    // if (response.status === 'success') {
                    //     alert(response.message);
                    //     var redirectUrl = "{{ url('ticket/Ticket-Assign?id=') }}" + eventId;
                    //     window.location.href = redirectUrl;
                    //     // Remove the assigned seat from all dropdowns
                    //     // $('select[name="subSection[]"]').find('option[value="' + seatNumber + '"]').remove();

                    //     location.reload();
                    // } else {
                    //     alert(response.message);
                    // }
                },
                error:function(xhr){
                    alert("Error: "+xhr.responseText);
                }

            });
            
        }

        // document.addEventListener("DOMContentLoaded", function() {
        $(document).on('change', '.first-dropdown', function() {
            var selectedValue = $(this).val(); // Get the selected value from the first dropdown
            var secondDropdown = $(this).closest('tr').find('.second-dropdown'); // Get the corresponding second dropdown
            var numberOfOptions = $(this).data('number-of-options'); // Get the dynamic number of options
            var eventId = $("#selectEvent").val(); // Get the event ID

            // Clear existing options in the second dropdown
            secondDropdown.empty();

            // Check if a valid selection was made
            if (selectedValue) {
                // Create an array of seat numbers to be checked
                var seatNumbers = [];
                // for (var i = 1; i <= numberOfOptions; i++) {
                //     seatNumbers.push(selectedValue + 'S' + i);
                // }

                // Send a single AJAX request to check all seat numbers at once
                $.ajax({
                    url: "{{ url('checkAssignValue') }}",
                    type: "POST",
                    data: {
                        eventId: eventId,
                        tableNumber: selectedValue,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response); // Check the entire response

                        var availableSeatNumbers = response.availableSeatNumbers;

                        if (Array.isArray(availableSeatNumbers)) {
                            // Append available seat numbers to the second dropdown
                            availableSeatNumbers.forEach(function(seatNumber) {
                                secondDropdown.append('<option value="' + seatNumber.place + '">' + seatNumber.seat + '</option>');
                            });

                            // If no seat numbers are available, show a default option
                            if (availableSeatNumbers.length === 0) {
                                secondDropdown.append('<option value="">No available seats</option>');
                            }
                        } else {
                            console.log("Unexpected response format:", availableSeatNumbers);
                            secondDropdown.append('<option value="">Error loading seats</option>');
                        }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("XHR: " + xhr.responseText);
                }
            });
            } else {
            // If no selection, show a default option
            secondDropdown.append('<option value="">Select a section first</option>');
            }
        });
// });
        var ticketprice=0;
        function assignTicket(node){
            let table = $(node).parents('tr').find('select')[0].value;
            let seat = $(node).parents('tr').find('select')[1].value;

            // ticketprice = parseInt($(node).data('price'));
            let ticketId = $(node).data('ticket-id');
            alert(table + " " + seat + " " + ticketId); return;



            if(table != '' && seat != ''){
                $('[name=ticketId]').val(ticketId);
                $('[name=table_name]').val(table);
                $('[name=table_num]').val(seat);
                $('#assignTicket').modal('toggle');
            } else {
                toastr.error('Please Select Seat First')
            }
        }

        function ViewAssignTicket(node){
            let ticketId = $(node).data('ticket-id');
            $.get('{{ url('/getassign-ticket') }}',{ticketId}).then((res)=>{
                $('#ViewassignTicket').modal('toggle');
                $('#ViewassignTicket .modal-body').html(res.html);
            })
        }

        function OpenreassignSeat(node){
            $(node).parent().parent().find('.btn-warning').show();
            $(node).closest('.parent-container').find(".assign-button").prop("disabled", false);
        }

        function ReassignSeat(node){
            let ticketId = $(node).data('ticket-id');
            let table_num = $(node).parent().parent().find('[name="subSection[]"]').val();
            $.post('{{url('/assign-ticket')}}',{ticketId,table_num,_token: '{{ csrf_token() }}'}).then(()=>{
                toastr.success('Ticket Re-assined Successfully');
            });

        }

        function unassignSeat(node){
            let ticketId = $(node).data('ticket-id');
            let eventId = $(node).data('event-id');
            var formData = new FormData();

            formData.append("ticketId", ticketId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
            // $.post(
            //     '{{url('unassign-ticket')}}',
            //     {ticketId,table_num,_token: '{{ csrf_token() }}'}).then(()=>{
            //     toastr.success('Ticket Re-assined Successfully');
            // });

            $.ajax({
                url:"{{ route ('unassign-ticket') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert("Seat successfully unassigned. Available for reallocation");
                    var redirectUrl = "{{ url('ticket/Ticket-Assign?id=') }}" + eventId;
                    window.location.href = redirectUrl;
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-success">' + response + '</div>');
                },
                error: function() {
                    alert('fail');
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while uploading.</div>');
                }
            });
            
        }

        function showTableTooltip(event, tableNum, tableType, tableName, sectionType) {
            
            event.stopPropagation(); // Prevent closing when clicking inside tooltip

            // Set tooltip content
            $("#tableInfo").html(`<strong>Section: ${tableNum}</strong><br>Type: ${tableType}<br>Name: ${tableName}`);
            $("#deleteTableToolTip").attr("data-table-num", tableNum);
            $(".show-details").attr("data-section-identifier", tableNum);
            $(".show-details").attr("data-section-type", sectionType);
            // $(".show-details").attr("data-section-type", tableNum);

            $(".hiddenTableNum").val(tableNum);
            $("#closeTableTooltip").css("display", "block");

            // Position tooltip near the clicked table
            let tooltip = $("#tableTooltip");
            tooltip.css({
                top: event.pageY - 50 + "px",
                left: event.pageX - 300 + "px",
                display: "block"
            });
        }

        $(document).on("click", "#closeTableTooltip" ,function (){
            $("#tableTooltip").hide();
        });

        $(document).on("click", "#deleteTableToolTip", function (){
            let tableNum = $(".hiddenTableNum").val();
            let eventId = $(".hiddenEventId").val();

            var confirmDeleteTable = confirm("Are you sure you want to delete");
            if(!confirmDeleteTable)
            {
                return;
            }

            var formData = new FormData();
            formData.append("tableNum", tableNum);
            formData.append("eventId", eventId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url:"{{ route ('delete-table-event-id') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var redirectUrl = "{{ url('ticket/Seat-Plan?id=') }}" + eventId;
                    window.location.href = redirectUrl;
                    // alert('success');
                    // location.reload();
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-success">' + response + '</div>');
                },
                error: function() {
                    alert('fail');
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while uploading.</div>');
                }
            });


        });

        $(document).on("click", ".seat", function (event) {
            var seatNumber = $(this).data("seat-number");
            var tableNumber = $(this).data("table-num");
            var eventId = $(this).data("event-id");
            var seatType = $(this).data("ticket-type");
            var ticketId = $(this).data("ticket-id");
            
            // alert(seatType + " " + eventId + " " + tableNumber + " " + seatNumber + " " + ticketId);
            
            var bgColor = $(this).css("background-color");
            // Convert RGB to HEX
            var hexColor = rgbToHex(bgColor);
            if (hexColor === "#6c757d")
            {
                return;
            }

            // Set tooltip content
            $("#seatInfo").text("Seat: " + seatNumber + ", Table: " + tableNumber);

            // Position tooltip next to the clicked seat
            var offset = $(this).offset();
            var seatWidth = $(this).outerWidth();
            var seatHeight = $(this).outerHeight();
            var tooltipWidth = $("#seatTooltip").outerWidth();
            var tooltipHeight = $("#seatTooltip").outerHeight();

            $(".seat-tooltip").css({
                top: offset.top - seatHeight - 20 + "px", // Move it up a bit
                left: offset.left + seatWidth / 2 - tooltipWidth / 2 - 180 + "px", // Center it horizontally
                display: "block"
            });


            if(seatType == "assignThisTicket")
            {
                $("#assignSeatToolTip").css("display", "block");

                $("#viewTicketToolTip").css("display", "none");
                $("#cancelTicketToolTip").css("display", "none");
                $("#unassignSeatToolTip").css("display","none");
            }
            else
            {
                $("#unassignSeatToolTip").css("display","block");
                $("#viewTicketToolTip").css("display", "block");
                $("#cancelTicketToolTip").css("display", "block");

                $("#assignSeatToolTip").css("display", "none");
            }

            // Store data in buttons
            $("#assignSeatToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId);
            $("#unassignSeatToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId);
            $("#viewTicketToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId).data("ticket-id", ticketId);
            $("#cancelTicketToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId).data("ticket-id", ticketId);
        });
        
        $(document).on("click", ".seat-grid", function (event) {
            var seatNumber = $(this).data("seat-number");
            var tableNumber = $(this).data("table-num");
            var eventId = $(this).data("event-id");
            var seatType = $(this).data("ticket-type");
            var ticketId = $(this).data("ticket-id");
            
            // alert(seatType + " " + eventId + " " + tableNumber + " " + seatNumber + " " + ticketId);
            
            var bgColor = $(this).css("background-color");
            // Convert RGB to HEX
            var hexColor = rgbToHex(bgColor);
            if (hexColor === "#6c757d")
            {
                return;
            }

            // Set tooltip content
            $("#seatInfo").text("Seat: " + seatNumber + ", Table: " + tableNumber);

            // Position tooltip next to the clicked seat
            var offset = $(this).offset();
            var seatWidth = $(this).outerWidth();
            var seatHeight = $(this).outerHeight();
            var tooltipWidth = $("#seatTooltip").outerWidth();
            var tooltipHeight = $("#seatTooltip").outerHeight();


            $(".seat-tooltip").css({
                top: offset.top - seatHeight - 20 + "px", // Move it up a bit
                left: offset.left + seatWidth / 2 - tooltipWidth / 2 - 180 + "px", // Center it horizontally
                display: "block"
            });


            if(seatType == "assignThisTicket")
            {
                $("#assignSeatToolTip").css("display", "block");

                $("#viewTicketToolTip").css("display", "none");
                $("#cancelTicketToolTip").css("display", "none");
                $("#unassignSeatToolTip").css("display","none");
            }
            else
            {
                $("#unassignSeatToolTip").css("display","block");
                $("#viewTicketToolTip").css("display", "block");
                $("#cancelTicketToolTip").css("display", "block");

                $("#assignSeatToolTip").css("display", "none");
            }

            // Store data in buttons
            $("#assignSeatToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId);
            $("#unassignSeatToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId);
            $("#viewTicketToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId).data("ticket-id", ticketId);
            $("#cancelTicketToolTip").data("seat-number", seatNumber).data("event-id", eventId).data("table-num", tableNumber).data("event-id", eventId).data("ticket-id", ticketId);
        });

        $(document).on("click", "#closeTooltip", function () {
            $("#seatTooltip").hide();
        });

        $(document).on("click", ".unassignThisTicket", function(){
            var seatNumber = $(this).data("seat-number");
            var eventId = $(this).data("event-id");

            var confirmDelete = confirm("Are you sure you want to unassign seat number " + seatNumber + "?");

            if (!confirmDelete) {
                return; // Stop execution if user clicks "Cancel"
            }

            var formData = new FormData();
            formData.append("seatNumber", seatNumber);
            formData.append("eventId", eventId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url:"{{ route ('unassign-ticket-seatnumber') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert("seat number: " + seatNumber + " is removed!");
                    var redirectUrl = "{{ url('ticket/Seat-Plan?id=') }}" + eventId;
                    window.location.href = redirectUrl;
                    // alert('success');
                    // location.reload();
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-success">' + response + '</div>');
                },
                error: function() {
                    alert('fail');
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while uploading.</div>');
                }
            });
        });

        $(".assignTicketModalFormSeatPlanning").on("submit", function(e) {
            var guestName = $(this).find("#holder_name").val().trim();
            var leaderName = $(this).find("#leader_name").val().trim();

            if (guestName === "" && leaderName === "") {
                alert("Either Guest Name or Leader Name must be provided.");
                e.preventDefault(); // Stop form submission
            }

        });

        $(document).on("click", ".assignThisTicket", function(){

            
            var seatNumber = $(this).data("seat-number");
            var eventId = $(this).data("event-id");
            var tableNum = $(this).data("table-num");

            $(".ticketSeatNumber").val(seatNumber);
            $(".ticketTableNumber").val(tableNum);
            $(".ticketEventId").val(eventId);
            $('#assignTicketModalSeatPlanning').modal('show');
        });

        $(document).on("click", ".viewTicket", function(){
            var seatNumber = $(this).data("seat-number");
            var eventId = $(this).data("event-id");
            var tableNum = $(this).data("table-num");

            var ticketId = $(this).data("ticket-id");

            var formData = new FormData();
            formData.append("ticketId", ticketId);
            formData.append("eventId", eventId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            var formDataObj = {};
            formData.forEach((value, key) => { formDataObj[key] = value; });

            var pdfUrl = "{{ route('ticket-PDF-Seating-Plan', ':id') }}".replace(':id', ticketId);

            $.ajax({
                url: pdfUrl,
                type: "GET",
                data: formDataObj,
                beforeSend: function() {
                    $("#saveTableAndSeatsResponse").html('<div class="alert alert-info">Adding Tables and Seats...</div>');
                },
                success: function(response) {
                    $(".modalBodyForTicketView").html(response);
                    $('#viewTicketSeatingPlan').modal('show');
                    // console.log(response);
                },
                error: function() {
                    $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while saving! '+ response +'</div>');
                }
            });

        });


        $(document).on("click", ".cancelThisTicket", function(){
            var seatNumber = $(this).data("seat-number");
            var eventId = $(this).data("event-id");
            var ticketId = $(this).data("ticket-id");

            var confirmDelete = confirm("Are you sure you want to cancel seat number " + seatNumber + "? You will not be able to use this ticket anymore!");

            if (!confirmDelete) {
                return; // Stop execution if user clicks "Cancel"
            }

            var formData = new FormData();
            formData.append("seatNumber", seatNumber);
            formData.append("eventId", eventId);
            formData.append("ticketId", ticketId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url:"{{ route ('cancel-ticket-seatnumber') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert("seat number: " + seatNumber + " is cancelled!");
                    var redirectUrl = "{{ url('ticket/Seat-Plan?id=') }}" + eventId;
                    window.location.href = redirectUrl;
                    // alert('success');
                    // location.reload();
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-success">' + response + '</div>');
                },
                error: function() {
                    alert('fail');
                    // $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while uploading.</div>');
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            var dropdownElement = document.getElementById('seatingDropdown');
            if (dropdownElement) {
                new bootstrap.Dropdown(dropdownElement);
            }
        });

        document.addEventListener('click', function (event) {
            var dropdownMenu = document.querySelector('.dropdown-menu.show'); // Check if dropdown is open
            if (dropdownMenu && !dropdownMenu.parentElement.contains(event.target)) {
                dropdownMenu.classList.remove('show'); // Hide the dropdown
            }
        });

        $("#addTicket").click(function(){
            var dropdownMenu = document.querySelector('.dropdown-menu.show');
            dropdownMenu.classList.remove('show');
            $('#addMoreTicketsModal').modal('show');
        });
        
        $("#addSection").click(function(){
            var dropdownMenu = document.querySelector('.dropdown-menu.show');
            dropdownMenu.classList.remove('show');
            $('#generateTableAndSeatsModal').modal('show');
        });

        $("#updateSection").click(function(){
            var dropdownMenu = document.querySelector('.dropdown-menu.show');
            console.log("Update Seating Section clicked!");
            dropdownMenu.classList.remove('show');
        });





        document.addEventListener("DOMContentLoaded", function() {

            $(document).on('click', '.assign-button', function() {
                var row = $(this).closest('tr');
                var ticketId = $(this).data('ticket-id');
                var eventId = $(this).data('event-id');
                var holderName = row.find('.holderName').val();
                var leaderName = row.find('.leaderName').val();
                var seatNumber = row.find('select[name="subSection[]"]').val();
                var tableNumber = row.find('select[name="ticketDivision[]"]').val();
                if(!holderName)
                {
                    alert("holder name is empty");
                    $(".ticketIdModalForm").val(ticketId);
                    $(".ticketSeatNumber").val(seatNumber);
                    $(".ticketTableNumber").val(tableNumber);
                    $("#table_name").val(tableNumber);
                    $("#table_num").val(seatNumber);
                    $('#assignTicketModal').modal('show');
                    return;
                }
                var holderEmail = row.find('input[name="holderEmail[]"]').val();

                $.ajax({
                    url: "{{url('/assign-ticket')}}",  // Update with your route
                    method: 'POST',
                    data: {
                    _token: "{{csrf_token()}}",
                        ticketId: ticketId,
                        holder_name: holderName,
                        leader_name: leaderName,
                        holderEmail: holderEmail,
                        seatNumber: seatNumber,
                        tableNumber: tableNumber
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            var redirectUrl = "{{ url('ticket/Ticket-Assign?id=') }}" + eventId;
                            window.location.href = redirectUrl;
                            // Remove the assigned seat from all dropdowns
                            // $('select[name="subSection[]"]').find('option[value="' + seatNumber + '"]').remove();

                            // location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error:function(xhr){
                        alert("Error: "+xhr.responseText);
                    }

                });

            });
        });

        function confirmAndOpenModal(eventTitle,eid) {
            document.getElementById("eventId").value=eid;
            $('#generateTicketsModal').modal('show');
            return false;
        }

        $(".btnAddTable").click(function() {
            $('#generateTableAndSeatsModal').modal('show');
        });

        $(".assignTicketModalForm").on("submit", function(e) {
            var guestName = $(this).find("#holder_name").val().trim();
            var leaderName = $(this).find("#leader_name").val().trim();
            
            if (guestName === "" && leaderName === "") {
                alert("Either Guest Name or Leader Name must be provided.");
                e.preventDefault(); // Stop form submission
            }

        });

        function saveTable()
        {
            var tablenumber = $('#tableNumber').val();
            var tablename = $('#tableName').val();
            var tableseats = $('#numSeats').val();
            var eventId = $('#eventId2').val();
            var tableType = $('.tableTypeSelect').val();

            var formData = new FormData();
            formData.append("tablenum", tablenumber);
            formData.append("tablename", tablename);
            formData.append("tableseats", tableseats);
            formData.append("tabletype", tableType);
            formData.append("eventId", eventId);
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            $.ajax({
                url:"{{ route ('saveTableAndSeats') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#saveTableAndSeatsResponse").html('<div class="alert alert-info">Adding Tables and Seats...</div>');
                },
                success: function(response) {
                    if(response == 'Table number or Number of seats cannot be empty')
                    {
                        $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">' + response + '</div>');
                    }
                    else
                    {
                        $("#saveTableAndSeatsResponse").html('<div class="alert alert-success">' + response + '</div>');
                    }
                },
                error: function() {
                    $("#saveTableAndSeatsResponse").html('<div class="alert alert-danger">An error occurred while saving! '+ response +'</div>');
                }
            });
        }

        function importTicketsModal(eventTitle,eid) {
            document.getElementById("eventId").value=eid;
            $('#importTicketsModal').modal('show');
            return false;
        }

        $("#submitImport").click(function() {
            var formData = new FormData();
            var file = $("#csvFile")[0].files[0];  // Get file from input
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            if (!file) {
                $("#importResponse").html('<div class="alert alert-danger">Please select a file.</div>');
                return;
            }

            formData.append("csvFile", file);
            formData.append("eventId", $("#eventId").val());
            formData.append("_token", csrfToken);

            $.ajax({
                url:"{{ route ('importTicket') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#importResponse").html('<div class="alert alert-info">Importing tickets...</div>');
                },
                success: function(response) {
                    $("#importResponse").html('<div class="alert alert-success">' + response + '</div>');
                },
                error: function() {
                    $("#importResponse").html('<div class="alert alert-danger">An error occurred while uploading.</div>');
                }
            });
        });

        $(document).on('click', '.show-details', function(e) {
                e.preventDefault();

                var type = $(".show-details").data("section-type");

                var tableNum = $(this).data('section-identifier'); 
                var identifier = $(".rowIdentifier").data('id'); // 'Row1' or 'Table1'

                // $('.seat-grid[data-table-num="' + tableNum + '"]').each(function() {
                //     var seatNumber = $(this).data('seat-number'); // Read seat number
                //     console.log(seatNumber);
                //     // seatNumbers.push(seatNumber);
                // });

                // return;

                var detailsHtml = '';

                // Clear previous details
                $('#detailsBody').empty();

                // Find the relevant details based on the clicked row or table
                if (type === 'grid') {
                    $('.seat-grid[data-table-num="' + tableNum + '"]').each(function() {
                        var seatNumber = $(this).data('seat-number'); // Read seat number
                        console.log(seatNumber);
                        // var rowId = $(this).find('.row-label a').data('id');

                        if (rowId === identifier) {
                            alert(rowId);
                            console.log("Seats found in row:", $(this).find('.seat-grid').length); // Debugging

                            $(this).find('.seat-grid').each(function() {
                                var seatText = $(this).text().trim();
                                var seatNumber = 'C' + identifier.split('Row')[1] + 'S' + seatText;
                                var tooltip = $(this).attr('title') || '';  // Default to empty string
                                var holderName = tooltip.includes('Name:') ? tooltip.split(',')[0].replace('Name: ', '') : '';
                                var holderEmail = tooltip.includes('Email:') ? tooltip.split(',')[1].replace(' Email: ', '') : '';

                                detailsHtml += '<tr><td>' + seatNumber + '</td><td>' + holderName + '</td><td>' + holderEmail + '</td></tr>';
                            });
                        }
                    });
                } else if (type === 'table') {
                    console.log("Looking for table with identifier: " + identifier);

                    $('.table-seatPlan').each(function() {
                        var tableId = $(this).find('a').data('id');
                        console.log("Checking tableId: " + tableId);

                        if (tableId === identifier) {
                            console.log("MATCH FOUND: " + tableId);

                            var $container = $(this).closest('.table-container');

                    if ($container.find('.seat').length > 0) {
                        console.log(".seat elements found!");
                    } else {
                        console.log("No .seat elements found!");
                    }
                            $container.find('.seat').each(function() {
                                var seatNumber = 'T' + identifier.split('Table')[1] + 'S' + $(this).text();
                                var tooltip = $(this).attr('title');
                                var holderName = tooltip.includes('Name:') ? tooltip.split(',')[0].replace('Name: ', '') : '';
                                var holderEmail = tooltip.includes('Email:') ? tooltip.split(',')[1].replace(' Email: ', '') : '';
                                console.log("Seat# " + seatNumber + ", Name: " + holderName + ", Email: " + holderEmail);
                                detailsHtml += '<tr><td>' + seatNumber + '</td><td>' + holderName + '</td><td>' + holderEmail + '</td></tr>';
                            });
                        }
                    });
                }

                $('#detailsBody').html(detailsHtml);
                $('#collapseSection').collapse('show');
        });
    </script>
    @stack('script')
  </body>
  </html>
