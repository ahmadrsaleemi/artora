<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/run', function () {
    $u = \App\Models\User::get();
    foreach ($u as $key => $value) {
    	$value->userPassword = Illuminate\Support\Facades\Hash::make('123456');
    	$value->save();
    }
});




Route::get('/', function(){
    return view('authentication.login');
})->name('login');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/user/Add-User', [UserController::class, 'addUser'])->middleware('auth')->name('add-user');
Route::post('registerUser',[UserController::class, 'registerUser'])->middleware('auth');
Route::get('/user/View-User',[UserController::class, 'viewUser'])->middleware('auth')->name('view-user');
Route::get('/deleteUser/{id}',[UserController::class, 'deleteUser'])->middleware('auth');
Route::post('updateUser',[UserController::class, 'updateUser'])->middleware('auth');
Route::get('/concept/Add-Concept', [EventController::class, 'addEvent'])->middleware('auth');
Route::get('/concept/View-Concept', [EventController::class, 'viewEvent'])->middleware('auth');
Route::post('/concept/AddSeatsToTable', [EventController::class, 'addSeatsToTable'])->middleware('auth');
Route::post('registerEvent',[EventController::class, 'registerEvent'])->name('register_event')->middleware('auth');
Route::get('/concept/updateConceptPage/{id}',[EventController::class, 'updateEventPage'])->middleware('auth');
Route::post('updateEvent',[EventController::class, 'updateEvent'])->middleware('auth');
Route::post('deleteTable',[EventController::class, 'deleteTable'])->name('delete-table-event-id')->middleware('auth');
Route::get('/event/deleteEvent/{id}',[EventController::class, 'deleteEvent'])->middleware('auth');
Route::get('/configuration/Add-Event-Type',[EventController::class, 'eventTypePage'])->middleware('auth')->name('add-event-type');
Route::post('addEventType',[EventController::class, 'addEventType'])->middleware('auth');
Route::post('/ticket/generateTicket', [TicketsController::class, 'generateTicket'])
    ->middleware('auth')
    ->name('generateTicket');
Route::post('/ticket/importTicket', [TicketsController::class, 'importTicket'])
    ->middleware('auth')
    ->name('importTicket');
Route::get('/ticket/View-Ticket', [TicketsController::class,'viewTicket'])->name('viewTicket');
Route::post('/ticket/saveTableAndSeats', [TicketsController::class,'saveTableAndSeats'])->name('saveTableAndSeats');
Route::post('selectEventTickets', [TicketsController::class,'selectEventTickets'])->middleware('auth');
Route::get('/ticket/Ticket-Assign',[TicketsController::class,'ticketAssign'])->middleware('auth')->name('ticketAssign');
Route::get('/ticket/scanned-tickets',[TicketsController::class,'ScannedTickets'])->name('scanned-tickets')->middleware('auth');

Route::post('getEventTickets', [TicketsController::class,'getEventTickets'])->middleware('auth');
Route::post('/assign-ticket', [TicketsController::class, 'assignTicket'])->name("assign-ticket")->middleware('auth');
Route::post('/assign-ticket-form', [TicketsController::class, 'assignTicketForm'])->name("assign-ticket-form")->middleware('auth');
Route::post('/assign-ticket-form-seating-plan', [TicketsController::class, 'assignTicketFormSeatingPlan'])->name("assign-ticket-form-seating-plan")->middleware('auth');
Route::get('/getassign-ticket', [TicketsController::class, 'GetassignTicket'])->middleware('auth');
Route::post('/unassign-ticket', [TicketsController::class, 'UnassignTicket'])->middleware('auth')->name('unassign-ticket');
Route::post('/unassign-ticket-seatnumber', [TicketsController::class, 'UnassignTicketSeatNumber'])->middleware('auth')->name('unassign-ticket-seatnumber');
Route::post('/cancel-ticket-seatnumber', [TicketsController::class, 'cancelTicketSeatNumber'])->middleware('auth')->name('cancel-ticket-seatnumber');
Route::post('/checkAssignValue', [TicketsController::class, 'checkAssignValue'])->middleware('auth');
Route::get('/ticket-PDF', [TicketsController::class, 'generateTicketPdf'])->middleware('auth')->name('ticket-PDF');
Route::get('/ticket-PDF-Seating-Plan', [TicketsController::class, 'generateTicketPdfSeatingPlan'])->middleware('auth')->name('ticket-PDF-Seating-Plan');
Route::get('/ticket/Seat-Plan', [TicketsController::class, 'seatPlanning'])->middleware('auth')->name('seatPlan');
Route::post('selectEventAssignTickets', [TicketsController::class,'selectEventAssignTickets'])->middleware('auth');
Route::post('generateSeatingPlan', [TicketsController::class,'generateSeatingPlan'])->middleware('auth');
Route::get('/ticket/Ticket-Type', [TicketsController::class,'ticketTypePage'])->middleware('auth')->name('ticketType');
Route::get('/ticket/Ticket-Type/delete', [TicketsController::class,'DeleteticketType'])->middleware('auth');

Route::post('ticketType', [TicketsController::class,'ticketType'])->middleware('auth');
Route::put('ticketType', [TicketsController::class,'UpdateTicketType'])->middleware('auth')->name('ticketType.update');

Route::get('/scanner', [TicketsController::class,'scanner'])->name('scanner');
Route::get('/ticketscan', [TicketsController::class,'ticketscan'])->name('ticketscan');

Route::get('/login', function(){
    return view('authentication.login');
})->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
