<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $with = ['airportFrom', 'airportTo'];

    public function airportFrom()
    {
        return $this->hasOne(Airport::class, 'id', 'from_id');
    }

    public function airportTo()
    {
        return $this->hasOne(Airport::class, 'id', 'to_id');
    }

    public  function setDate($date)
    {
        $this->date = $date;
    }

    public function getAvailabitilty()
    {
        $passengers = 0;

        if ($booking = Booking::where('flight_from', $this->id)->
        where('flight_back', $this->id)->first()) {
            $passengers = count($booking->passengers);
        }

        return 60 - $passengers;
    }
}
