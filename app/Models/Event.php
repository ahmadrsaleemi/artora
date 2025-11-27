<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $primaryKey = 'eid';

    public function detail(){
    	return $this->hasOne(EventDetails::class,'eid','eid');
    }

    public function tickettypes(){
    	return $this->hasMany(EventTicketType::class,'event_id','eid');
    }

}
