<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function addCard(Request $request)
    {

    }
    public function deleteCard(Request $request,$id)
    {
        $delete=Card::where('id',$id)->delete();
        $this->successData('message','Card Deleted');
        return $this->response();
    }
}
