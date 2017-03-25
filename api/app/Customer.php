<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    const ID='id';
    const NAME='name';
    const EMAIL='email';
    const PHONE='phone';
    const PASSWORD='password';
    const REMEMBER_TOKEN='remember_token';
    const ROLE=0;
    const DATA=[Customer::ID,Customer::NAME,Customer::EMAIL,Customer::PHONE/*,Customer::PASSWORD*/];

    protected $fillable=[Customer::ID,Customer::NAME,Customer::EMAIL,Customer::PHONE,Customer::PASSWORD];

    protected $hidden=[Customer::PASSWORD,Customer::REMEMBER_TOKEN,Customer::ROLE];
}
