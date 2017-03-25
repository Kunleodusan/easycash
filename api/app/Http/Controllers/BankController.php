<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BankController extends Controller
{

    /*Create A bank*/
    public function create(Request $request)
    {
        /*Retrieve request & Validate*/
        $data=$request->all();
        $data['email']=strtolower($data['email']);
        //return $data;

        $check=$this->RequestValidate($data,[
            'name' => 'required|max:100',
            'email' => 'required|email|unique:banks|max:100',
            'phone' => 'required|numeric|unique:banks',
            //'coach_id' => 'required|numeric|exists:coaches',
            'password' => 'required|max:15']);

        if (!$check) return $this->response();
        /*Process Request*/
        $data['password']=Hash::make($data['password']);

        $user=Bank::create($data);
        if($user){
            $user=collect($user)->except(['password']);
            $this->successData('bank',$user);
            $oauth=$this->generateToken($request,$user,Bank::ROLE);
            $this->successData('auth',$oauth);
            /*@todo generate dashboard & welcome email.*/
            //$this->successData('dashboard',HomeController::getDashboard($user['id'],Student::ROLE));
            //Mail::to($user['email'])->send(new RegistrationComplete());
        }
        /*Return Response*/
        return $this->response();
    }

    /*Authenticate A bank. Obsolete*/
    public function auth(Request $request){
        /*Retrieve request & Validate*/
        $data=$request->all();
        //return $data;

        $check=$this->RequestValidate($data,[
            'email' => 'email|max:100|exists:banks',
            'password' => 'required|max:15']);
        if (!$check) return $this->response();

        /*Process Request*/
        $user=Bank::where([Bank::EMAIL=>$request->email])->orWhere([Bank::PHONE=>$request->phone])->first();
        if (Hash::check($data['password'], $user['password'])) {
            $this->successData('bank',$user);
            $this->successData('auth',$this->generateToken($request, $user,Bank::ROLE));
        }
        else{
            $this->errorMsg('Bank Not Found');
        }
        return $this->response();
    }

    /*Update A bank*/
    public function update(Request $request,$id)
    {
        $data=$request->all();
        $query=collect($data)->only(Bank::DATA)->toArray();
        $data=array_add($data,Bank::ID,$id);
        $check=$this->RequestValidate($data,[
            'id' => 'required|exists:banks',
            'name' => 'max:100',
            'email' => 'email',
            'phone' => 'numeric',
            'password' => 'max:15']);
        if (!$check) return $this->response();

        /*Check if image exists*/
        if($request->hasFile('image')){
            $query=$this->saveFile($request->file('image'),$query,'uploads/image','image');
        }

        $user=Bank::find($id);
        $userData=$user->update($query);
        if ($userData){
            $this->successData('message','Updated');
            $this->successData('bank',$user);
        }

        return $this->response();
    }

    /*Get A bank*/
    public function get(Request $request,$id)
    {
        $bank=Bank::where('id',$id)->first();

        if ($bank){
            $this->successData('bank',$bank);
        }
        else $this->errorMsg('Bank Not Found');

        /*Return Response*/
        return $this->response();
    }

    /*
     * Get Profile of a bank
     * @param int
     * @return array
     * */

    /*Get All banks*/
    public function getAll()
    {
        $bank=Bank::paginate(20);
        $this->successData('banks',$bank);

        /*Return Response*/
        return $this->response();
    }
}
