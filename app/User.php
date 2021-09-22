<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
        [
            'first_name',
            'last_name',
            'phone',
            'password',
            'document_number'
        ];


    public function logout()
    {
        $this->api_token = null;
        $this->save();
    }

    public function setToken()
    {
        $token = $this->api_token = Str::random(32);
        $this->save();

        return $token;
    }
}
