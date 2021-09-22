<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'cost' => $this->getCost(),
            'flights' => FlightResource::collection($this->flightBack  ? collect([$this->flightFrom, $this->flightBack]) : $this->flightFrom),
            'passengers' => PassengerResource::collection($this->passengers),
        ];
    }
}
