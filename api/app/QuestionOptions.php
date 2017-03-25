<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model
{
    const QUESTION_ID='question_id';
    const OPTION='option';

    const DATA=[
      QuestionOptions::QUESTION_ID,
      QuestionOptions::OPTION,
    ];

    protected $fillable=[
      QuestionOptions::QUESTION_ID,
      QuestionOptions::OPTION,
    ];
}
