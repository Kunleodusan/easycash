<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    const ID='id';
    const USER_ID='user_id';
    const CLIENT_ID='client_id';
    const REVOKED='revoked';
    const ROLE='role';
    const ACCESS_TOKEN='access_token';
    const REFRESH_KEY='refresh_key';
    const EXPIRES_AT='expires_at';

    protected $fillable=[
        Token::ID,
        Token::USER_ID,
        Token::CLIENT_ID,
        Token::REVOKED,
        Token::ROLE,
        Token::ACCESS_TOKEN,
        Token::REFRESH_KEY,
        Token::EXPIRES_AT
    ];

    protected $hidden=[
        Token::EXPIRES_AT,
        Token::CREATED_AT,
        Token::UPDATED_AT
    ];
}
