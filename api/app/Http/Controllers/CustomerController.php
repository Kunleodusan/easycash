<?php

namespace App\Http\Controllers;

use App\Card;
use App\Customer;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    /*Create A student*/
    public function create(Request $request)
    {
        /*Retrieve request & Validate*/
        $data=$request->all();
        $data['email']=strtolower($data['email']);
        //return $data;

        $check=$this->RequestValidate($data,[
            'name' => 'required|max:100',
            'email' => 'required|email|unique:customers|max:100',
            'phone' => 'required|numeric|unique:customers',
            //'coach_id' => 'required|numeric|exists:coaches',
            'password' => 'required|max:15']);

        if (!$check) return $this->response();
        /*Process Request*/
        $data['password']=Hash::make($data['password']);

        $user=Customer::create($data);
        if($user){
            $user=collect($user)->except(['password']);
            $this->successData('customer',$user);
            $oauth=$this->generateToken($request,$user,Customer::ROLE);
            $this->successData('auth',$oauth);

            $this->successData('cards',$this->cards($user['id']));
            $this->successData('pending',$this->pending($user['id']));
            $this->successData('transactions',$this->transactions($user['id']));
            /*@todo generate dashboard & welcome email.*/
            //$this->successData('dashboard',HomeController::getDashboard($user['id'],Student::ROLE));
            //Mail::to($user['email'])->send(new RegistrationComplete());
        }
        /*Return Response*/
        return $this->response();
    }

    /*Authenticate A student. Obsolete*/
    public function auth(Request $request){
        /*Retrieve request & Validate*/
        $data=$request->all();
        //return $data;

        $check=$this->RequestValidate($data,[
            'email' => 'email|max:100|exists:customers',
            'phone' => 'required_without:email|numeric',
            'password' => 'required|max:15']);
        if (!$check) return $this->response();

        /*Process Request*/
        $user=Customer::where([Customer::EMAIL=>$request->email])->orWhere([Customer::PHONE=>$request->phone])->first();
        if (Hash::check($data['password'], $user['password'])) {
            $this->successData('customer',$user);
            $this->successData('cards',$this->cards($user['id']));
            $this->successData('pending',$this->pending($user['id']));
            $this->successData('transactions',$this->transactions($user['id']));
            $this->successData('auth',$this->generateToken($request, $user,Customer::ROLE));
        }
        else{
            $this->errorMsg('Customer Not Found');
        }
        return $this->response();
    }

    /*Update A Student*/
    public function update(Request $request,$id)
    {
        $data=$request->all();
        $query=collect($data)->only(Customer::DATA)->toArray();
        $data=array_add($data,Customer::ID,$id);
        $check=$this->RequestValidate($data,[
            'id' => 'required|exists:customers',
            'name' => 'max:100',
            'summary' => 'string',
            'image' => 'image',
            'email' => 'email',
            'phone' => 'numeric',
            'password' => 'max:15']);
        if (!$check) return $this->response();

        /*Check if image exists*/
        if($request->hasFile('image')){
            $query=$this->saveFile($request->file('image'),$query,'uploads/image','image');
        }

        $user=Customer::find($id);
        $userData=$user->update($query);
        if ($userData){
            $this->successData('message','Updated');
            $this->successData('customer',$user);
        }

        return $this->response();
    }

    /*Get A Customer*/
    public function get(Request $request,$id)
    {
        $customer=Customer::where('id',$id)->first();

        if ($customer){
            $this->successData('customer',$customer);
        }
        else $this->errorMsg('Customer Not Found');

        /*Return Response*/
        return $this->response();
    }

    /*
     * Get Profile of a customer
     * @param int
     * @return array
     * */

    /*Get All Customers*/
    public function getAll()
    {
        $customer=Customer::paginate(20);
        $this->successData('customers',$customer);

        /*Return Response*/
        return $this->response();
    }

    private function cards($id)
    {
        $cards=Card::where('customer_id',$id)->get();

        $cards=$this->jsonUnstring($cards);
        return $cards;
    }
    private function transactions($id)
    {
        $completed=Task::where([
            'customer_id'=>$id,
            'status'=>1
        ])->limit(5)->get();
        $completed=$this->jsonUnstring($completed);
        return $completed;
    }
    private function pending($id)
    {
        $pending=Task::where([
            'customer_id'=>$id,
            'status'=>0
        ])->get();
        $pending=$this->jsonUnstring($pending);
        return $pending;
    }

    private function jsonUnstring($data)
    {

        if(count($data)>0){
            foreach ($data as &$card){
                $card['card_detail']=json_decode($card['card_detail']);
            }
        }
        return $data;
    }


}
