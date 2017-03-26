<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const CUSTOMER_ID='customer_id';
    const ACTION='action';
    const AMOUNT='amount';
    const CARD_ID='cardid';
    const CARD_NO='cardno';
    const CARD_DETAIL='card_detail';
    const STATUS='status';

    const DATA=[
      Task::CUSTOMER_ID,Task::ACTION,Task::AMOUNT,Task::CARD_NO,Task::CARD_ID
    ];

    protected $fillable=[
      Task::CUSTOMER_ID,Task::ACTION,Task::AMOUNT,Task::STATUS,Task::CARD_NO,Task::CARD_DETAIL
    ];
}
