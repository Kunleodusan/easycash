<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function addCard(Request $request)
    {
        #$key=config('app.flutterwave_api_key');

        /*Retrieve request & Validate*/
        $data=$this->getData($request->all(),Card::DATA);
        //return $data;

        $check=$this->RequestValidate($data,[
            'cardno' => 'required|integer',
            'customer_id' => 'required|numeric|exists:customers,id'
        ]);

        if (!$check) return $this->response();

        $card=substr($request->cardno,0,9);

        #verify the card BIN @todo verify the card balance

        $cardData=$this->verifyBIN($card);
        //return json_encode($cardData);

        #card validated as true
        if($cardData['status']==true){
            #customer wants to save card
            if($request->save==1){
                $data['card_detail']=json_encode($cardData);
                //return $data;
                $card=Card::create($data);
                $this->successData('card',$card);
            }
            $message=$this->generateMessage($cardData,$request->save);
            $this->successData('message',$message);
            return $this->response();
        }

        else $this->errorMsg($cardData);
        return $this->response();

    }
    public function deleteCard(Request $request,$id)
    {
        $delete=Card::where('id',$id)->delete();
        $this->successData('message','Card Deleted');
        return $this->response();
    }

    private function generateMessage($cardData,$save)
    {
        $message='Your '.$cardData['message']['bank']['name'].' '.$cardData['message']['type'].' '.$cardData['message']['scheme'].' in '
                .$cardData['message']['country']['name'].' has been verified.';
        ;
        if($save==1){
            $message=$message.' Your card details have also been saved.';
        }
        return $message;
    }
}
