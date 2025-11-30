<aside>
	<div id="sidebar" class="nav-collapse ">
		<!-- sidebar menu start-->
		<ul class="sidebar-menu" id="nav-accordion">
			<li>
				<a href="{{ route('dashboard') }}">
					<i class="fa fa-dashboard"></i>
					<span>Dashboard</span>
				</a>
			</li>
			
			
			<li class="sub-menu">
				<a href="/concept/View-Concept">
					<i class="fa fa-calendar-o"></i>
					<span>Concept</span>
				</a>
				
			</li>
			<li class="sub-menu">
				<a href="{{route('viewTicket')}}">
					<i class="fa fa-money"></i>
					<span> View Tickets</span>
				</a>
			</li>
			<li class="sub-menu">
				<a href="{{route('ticketAssign')}}">
					<i class="fa fa-money"></i>
					<span>  Assign Tickets</span>
				</a>
			</li>
			<li class="sub-menu">
				<a href="{{route('seatPlan')}}">
					<i class="fa fa-money"></i>
					<span>  View Seating Plan</span>
				</a>
			</li>
			<li class="sub-menu">
				<a href="{{route('scanner')}}">
					<i class="fa fa-money"></i>
					<span> Scanner</span>
				</a>
			</li>
			<li class="sub-menu">
				<a href="{{route('scanned-tickets')}}">
					<i class="fa fa-money"></i>
					<span> Scanned Tickets</span>
				</a>
			</li>
			{{--            <li class="sub-menu">--}}
				{{--                <a href="">--}}
					{{--                    <i class="fa fa-money"></i>--}}
					{{--                    <span>Tickets</span>--}}
					{{--                </a>--}}
					{{--                <ul class="sub">--}}
						
						{{--                    <li><a href="/ticket/Ticket-Assign">Assign</a></li>--}}
						{{--                    <li><a href="/ticket/View-Ticket">View</a></li>--}}
						{{--                    <li><a href="/ticket/Seat-Plan">View Seating Plan</a></li>--}}
						{{--                    <li><a href="{{ route('scanner') }}">Scanner</a></li>--}}
						{{--                    <li><a href="{{ route('scanned-tickets') }}">Scanned Tickets</a></li>--}}
						{{--                </ul>--}}
						{{--            </li>--}}
						
						
						@if (Auth::check() && Auth::user()->userType == 0)
						<li class="sub-menu">
							<a href="{{route('add-event-type')}}">
								<i class="fa fa-cog"></i>
								<span>Add Event Type</span>
							</a>
							
						</li>
						<li class="sub-menu">
							<a href="{{route('ticketType')}}">
								<i class="fa fa-cog"></i>
								<span>Add Ticket Type</span>
							</a>
							
						</li>
						<li class="sub-menu">
							<a href="{{ route('view-user') }}">
								<i class="fa fa-user"></i>
								<span>View Users</span>
							</a>
					
						</li>
						<li class="sub-menu">
							<a href="{{ route('add-user') }}">
								<i class="fa fa-user"></i>
								<span>Create Users</span>
							</a>
						</li>
						{{--                <li class="sub-menu">--}}
							{{--                    <a href="">--}}
								{{--                        <i class="fa fa-cog"></i>--}}
								{{--                        <span>Configuration</span>--}}
								{{--                    </a>--}}
								{{--                    <ul class="sub">--}}
									{{--                        <li><a href="/configuration/Add-Event-Type">Add Event Type</a></li>--}}
									{{--                        <li><a href="/ticket/Ticket-Type">Add Ticket Type</a></li>--}}
									{{--                    </ul>--}}
									{{--                </li>--}}
									
									@endif
									
									
									{{-- <li class="sub-menu">
										<a href="javascript:;" class="active">
											<i class="fa fa-cogs"></i>
											<span>Components</span>
										</a>
										<ul class="sub">
											<li><a  href="grids.html">Grids</a></li>
											<li class="active"><a  href="calendar.html">Calendar</a></li>
											<li><a  href="gallery.html">Gallery</a></li>
											<li><a  href="todo_list.html">Todo List</a></li>
											<li><a  href="draggable_portlet.html">Draggable Portlet</a></li>
										</ul>
									</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class="fa fa-tasks"></i>
					<span>Form Stuff</span>
				</a>
				<ul class="sub">
					<li><a  href="form_component.html">Form Components</a></li>
					<li><a  href="advanced_form_components.html">Advanced Components</a></li>
					<li><a  href="form_wizard.html">Form Wizard</a></li>
					<li><a  href="form_validation.html">Form Validation</a></li>
					<li><a  href="dropzone.html">Dropzone File Upload</a></li>
					<li><a  href="inline_editor.html">Inline Editor</a></li>
					<li><a  href="image_cropping.html">Image Cropping</a></li>
					<li><a  href="file_upload.html">Multiple File Upload</a></li>
				</ul>
			</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class="fa fa-th"></i>
					<span>Data Tables</span>
				</a>
				<ul class="sub">
					<li><a  href="basic_table.html">Basic Table</a></li>
					<li><a  href="responsive_table.html">Responsive Table</a></li>
					<li><a  href="dynamic_table.html">Dynamic Table</a></li>
					<li><a  href="editable_table.html">Editable Table</a></li>
				</ul>
			</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class=" fa fa-envelope"></i>
					<span>Mail</span>
				</a>
				<ul class="sub">
					<li><a  href="inbox.html">Inbox</a></li>
					<li><a  href="inbox_details.html">Inbox Details</a></li>
				</ul>
			</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class=" fa fa-bar-chart-o"></i>
					<span>Charts</span>
				</a>
				<ul class="sub">
					<li><a  href="morris.html">Morris</a></li>
					<li><a  href="chartjs.html">Chartjs</a></li>
					<li><a  href="flot_chart.html">Flot Charts</a></li>
					<li><a  href="xchart.html">xChart</a></li>
				</ul>
			</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class="fa fa-shopping-cart"></i>
					<span>Shop</span>
				</a>
				<ul class="sub">
					<li><a  href="product_list.html">List View</a></li>
					<li><a  href="product_details.html">Details View</a></li>
				</ul>
			</li>
			<li>
				<a href="google_maps.html" >
					<i class="fa fa-map-marker"></i>
					<span>Google Maps </span>
				</a>
			</li>
			<li class="sub-menu">
				<a href="javascript:;">
					<i class="fa fa-comments-o"></i>
					<span>Chat Room</span>
				</a>
				<ul class="sub">
					<li><a  href="lobby.html">Lobby</a></li>
					<li><a  href="chat_room.html"> Chat Room</a></li>
				</ul>
			</li>
			<li class="sub-menu">
				<a href="javascript:;" >
					<i class="fa fa-glass"></i>
					<span>Extra</span>
				</a>
				<ul class="sub">
					<li><a  href="blank.html">Blank Page</a></li>
					<li><a  href="sidebar_closed.html">Sidebar Closed</a></li>
					<li><a  href="people_directory.html">People Directory</a></li>
					<li><a  href="coming_soon.html">Coming Soon</a></li>
					<li><a  href="lock_screen.html">Lock Screen</a></li>
					<li><a  href="profile.html">Profile</a></li>
					<li><a  href="invoice.html">Invoice</a></li>
					<li><a  href="project_list.html">Project List</a></li>
					<li><a  href="project_details.html">Project Details</a></li>
					<li><a  href="search_result.html">Search Result</a></li>
					<li><a  href="pricing_table.html">Pricing Table</a></li>
					<li><a  href="faq.html">FAQ</a></li>
					<li><a  href="fb_wall.html">FB Wall</a></li>
					<li><a  href="404.html">404 Error</a></li>
					<li><a  href="500.html">500 Error</a></li>
				</ul>
			</li> --}}
			{{-- <li>
				<a  href="login.html">
					<i class="fa fa-user"></i>
					<span>Login Page</span>
				</a>
			</li> --}}

			<!--multi level menu start-->
			{{-- <li class="sub-menu">
				<a href="javascript:;" >
					<i class="fa fa-sitemap"></i>
					<span>Multi level Menu</span>
				</a>
				<ul class="sub">
					<li><a  href="javascript:;">Menu Item 1</a></li>
					<li class="sub-menu">
						<a  href="boxed_page.html">Menu Item 2</a>
						<ul class="sub">
							<li><a  href="javascript:;">Menu Item 2.1</a></li>
							<li class="sub-menu">
								<a  href="javascript:;">Menu Item 3</a>
								<ul class="sub">
									<li><a  href="javascript:;">Menu Item 3.1</a></li>
									<li><a  href="javascript:;">Menu Item 3.2</a></li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</li> --}}
			<!--multi level menu end-->

		</ul>
		<!-- sidebar menu end-->
	</div>
</aside>
