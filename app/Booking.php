<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'flight_from',
        'date_from',
        'flight_back',
        'date_back',
        'code'
    ];

    protected $with = ['flightFrom', 'flightBack'];

    public function flightFrom()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_from');
    }

    public function flightBack()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_back');
    }

    public function passengers() {
        return $this->hasMany(Passenger::class, 'booking_id', 'id');
    }

    public function getCost()
    {
        $cost = $this->flightFrom->cost;

        if ($this->flightBack) {
           $cost += $this->flightBack->cost;
        }

        return $cost * count($this->passengers);
    }
}
