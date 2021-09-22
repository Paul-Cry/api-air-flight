<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'document_number'
    ];
}
