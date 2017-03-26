<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    const CUSTOMER_ID='customer_id';
    const CARDNO='cardno';
    const SAVE='save';
    const CARD_DETAIL='card_detail';

    const DATA=[
        Card::CUSTOMER_ID,Card::CARDNO,Card::CARD_DETAIL
    ];

    protected $fillable=[
        Card::CUSTOMER_ID,Card::CARDNO,Card::CARD_DETAIL
    ];
}
