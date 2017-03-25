<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    const ID='id';
    const NAME='name';
    const EMAIL='email';
    const PHONE='phone';
    const PASSWORD='password';
    const REMEMBER_TOKEN='remember_token';
    const ROLE=1;
    const DATA=[Bank::ID,Bank::NAME,Bank::EMAIL,Bank::PHONE/*,Bank::PASSWORD*/];

    protected $fillable=[Bank::ID,Bank::NAME,Bank::EMAIL,Bank::PHONE,Bank::PASSWORD];

    protected $hidden=[Bank::PASSWORD,Bank::REMEMBER_TOKEN,Bank::ROLE];
}
