<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    const ID='id';
    const NAME='name';
    const EMAIL='email';
    const PHONE='phone';
    const PASSWORD='password';
    const REMEMBER_TOKEN='remember_token';
    const ROLE=2;
    const DATA=[Admin::ID,Admin::NAME,Admin::EMAIL,Admin::PHONE/*,Admin::PASSWORD*/];

    protected $fillable=[Admin::ID,Admin::NAME,Admin::EMAIL,Admin::PHONE,Admin::PASSWORD];

    protected $hidden=[Admin::PASSWORD,Admin::REMEMBER_TOKEN,Admin::ROLE];
}
