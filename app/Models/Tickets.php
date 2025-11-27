<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;
    protected $primaryKey = 'ticketId';

    public function tickettypes(){
    	return $this->hasMany(EventTicketType::class,'event_id','eid');
    }


    public function event(){
    	return $this->belongsTo(Event::class,'eventId','eid');
    }
}
