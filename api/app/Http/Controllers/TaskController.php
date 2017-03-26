<?php

namespace App\Http\Controllers;

use App\Card;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function addTask(Request $request)
    {

        $data=$this->getData($request->all(),Task::DATA);
        //return $data;
        $info=$request->all();

        $check=$this->RequestValidate($data,[
            'action' => 'required|string',
            'amount' => 'integer',
            'cardid' => 'integer|exists:cards,id',
            'cardno' => 'integer|required_without:cardid',
            'customer_id' => 'required|numeric|exists:customers,id'
        ]);

        if (!$check) return $this->response();

        #check if using existing card
        if(isset($data['cardid'])){
            //return 'fetching card data';
            $card=Card::where('id',$data['cardid'])->first();
        }
        #else verify bvn
        else{
            //return 'verifying card';
            $card['cardno']=$data['cardno'];

            $bin=$this->verifyBIN($data['cardno']);
            #successful card verification
            if($bin['status']){
                $card['card_detail']=json_encode($bin);
            }
            //could not verify card.
            else{
                $this->errorMsg('invalid card details');
                return $this->response();
            }
        }

        $save=[
          'cardno'=>$card['cardno'],
          'card_detail'=>$card['card_detail'],
          'action'=>$data['action'],
          'amount'=>$data['amount'],
          'customer_id'=>$data['customer_id'],
          'status'=>0
        ];

        #else verify card and load data.
        $create=Task::create($save);
        $this->successData('message','Transaction has been created. Go to the nearest atm');
        $this->successData('task',$create);
        return $this->response();
    }

    public function cancelTask(Request $request,$id)
    {
        Task::where('id',$id)->delete();
        $this->successData('message','Task canceled');
        return $this->response();
    }

    public function verifyTask(Request $request,$id)
    {
        Task::where('id',$id)->update([
            'status'=>1
        ]);
        $this->successData('message','Task completed');
        return $this->response();
    }
}
