<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    const TITLE='title';
    const BANK_ID='bank_id';

    const DATA=[
        Question::TITLE,Question::BANK_ID
    ];

    protected $fillable=[
      Question::TITLE,Question::BANK_ID
    ];

    public function options()
    {
        return $this->hasMany('App\QuestionOptions');
    }
}
