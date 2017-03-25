<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    /*Create An admin*/
    public function create(Request $request)
    {
        /*Retrieve request & Validate*/
        $data=$request->all();
        $data['email']=strtolower($data['email']);
        //return $data;

        $check=$this->RequestValidate($data,[
            'name' => 'required|max:100',
            'email' => 'required|email|unique:admins|max:100',
            'phone' => 'required|numeric|unique:admins',
            //'coach_id' => 'required|numeric|exists:coaches',
            'password' => 'required|max:15']);

        if (!$check) return $this->response();
        /*Process Request*/
        $data['password']=Hash::make($data['password']);

        $user=Admin::create($data);
        if($user){
            $user=collect($user)->except(['password']);
            $this->successData('admin',$user);
            $oauth=$this->generateToken($request,$user,Admin::ROLE);
            $this->successData('oauth',$oauth);
            /*@todo generate dashboard & welcome email.*/
            //$this->successData('dashboard',HomeController::getDashboard($user['id'],Student::ROLE));
            //Mail::to($user['email'])->send(new RegistrationComplete());
        }
        /*Return Response*/
        return $this->response();
    }

    /*Authenticate An admin. Obsolete*/
    public function auth(Request $request){
        /*Retrieve request & Validate*/
        $data=$request->all();
        //return $data;

        $check=$this->RequestValidate($data,[
            'email' => 'email|max:100|exists:admins',
            'password' => 'required|max:15']);
        if (!$check) return $this->response();

        /*Process Request*/
        $user=Admin::where([Admin::EMAIL=>$request->email])->orWhere([Admin::PHONE=>$request->phone])->first();
        if (Hash::check($data['password'], $user['password'])) {
            $this->successData('admin',$user);
            $this->successData('auth',$this->generateToken($request, $user,Admin::ROLE));
        }
        else{
            $this->errorMsg('Admin Not Found');
        }
        return $this->response();
    }

    /*Update An admin*/
    public function update(Request $request,$id)
    {
        $data=$request->all();
        $query=collect($data)->only(Admin::DATA)->toArray();
        $data=array_add($data,Admin::ID,$id);
        $check=$this->RequestValidate($data,[
            'id' => 'required|exists:admins',
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

        $user=Admin::find($id);
        $userData=$user->update($query);
        if ($userData){
            $this->successData('message','Updated');
            $this->successData('admin',$user);
        }

        return $this->response();
    }

    /*Get An admin*/
    public function get(Request $request,$id)
    {
        $admin=Admin::where('id',$id)->first();

        if ($admin){
            $this->successData('admin',$admin);
        }
        else $this->errorMsg('Admin Not Found');

        /*Return Response*/
        return $this->response();
    }

    /*
     * Get Profile of an admin
     * @param int
     * @return array
     * */

    /*Get All admins*/
    public function getAll()
    {
        $admin=Admin::paginate(20);
        $this->successData('admins',$admin);

        /*Return Response*/
        return $this->response();
    }
}
