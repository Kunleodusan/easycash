<?php

namespace App\Http\Controllers;

use App\Token;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator as Validator;

class Controller extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $data=[
        'status'=>false,
    ];
    protected $success=true;
    protected $error=false;
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function RequestValidate($request,$rules)
    {
        $validRequest=Validator::make($request,$rules);
        if($validRequest->fails()){
            $this->errorMsg($validRequest->errors()->first());
            return false;
        }
        else return true;
    }

    public function errorMsg($msg)
    {
        $this->data['status']=$this->error;
        array_forget($this->data,'data');
        $this->data['error']=$msg;
    }
    public function successData($key,$msg)
    {
        $this->data['status']=$this->success;
        $this->data['data'][$key]=$msg;
    }

    public function response($code=null)
    {
        if ($this->data['status']==false){
            if(is_null($code)){$code=400;}
            return response()
                ->json($this->data,$code);
        }
        else return response()
            ->json($this->data,200);
    }

    public function generateAccessToken()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
    }

    public function generateClient_Secret()
    {
        $oauth= bin2hex(\OAuthProvider::generateToken(32,false));
        return strtoupper($oauth);
    }

    public function generateToken(\Illuminate\Http\Request $request,$user,$role)
    {
        $oauth=  Token::create(
            [
                Token::USER_ID=>$user['id'],
                Token::REVOKED=>0,
                Token::ROLE=>$role,
                Token::ACCESS_TOKEN=>$this->generateAccessToken(),
                Token::REFRESH_KEY=>$this->generateRefreshKey(),
                Token::EXPIRES_AT=>Carbon::now()->addMinute(30)->toDateTimeString()
            ]
        );

        return collect($oauth)->except([Token::ID,Token::CLIENT_ID,Token::USER_ID,Token::ROLE]);

    }
    public function generateRefreshKey()
    {
        return strtoupper(str_random(15));
    }

    public function except($inputArray,$items)
    {
        $data=collect($inputArray)->forget($items);
        return $data;
    }
    public function exceptAll($inputArray,$items)
    {
        $data=collect($inputArray)->each(function($item) use ($items){
            collect($item)->forget($items);
        });
        return $data;
    }

    public function getData($inputArray,$arrayToGet)
    {
        $data=collect($inputArray)->only($arrayToGet)->toArray();
        return $data;
    }

    public function getClientDetails(\Illuminate\Http\Request $request)
    {
        $Basicauth=explode(':',base64_decode(str_replace('Basic ','',$request->header('Authorization'))));

        if (count($Basicauth)<2){
            $this->errorMsg('Invalid Client Details');
            return false;
        }
        $header=[
            'client_id'=>$Basicauth[0],
            'client_secret'=>$Basicauth[1]
        ];
        return $header;
    }

    /*Get the user from the access token
    | @return array
    */
    public function getUser($access_token)
    {
        $token=Token::where(Token::ACCESS_TOKEN,$access_token)->first();
        /*Check if it could get the token successfully*/
        if($token){
            switch ($token['role']){
                case Admin::ROLE:
                    $user=Admin::where('id',$token['user_id'])->first();
                    $user['type']='admin';
                    return $user;
                    break;
                case Employer::ROLE:
                    $user=Employer::where('id',$token['user_id'])->first();
                    $user['type']='employer';
                    return $user;
                    break;
                case Coach::ROLE:
                    $user=Coach::where('id',$token['user_id'])->first();
                    $user['type']='coach';
                    return $user;
                    break;
                case Student::ROLE:
                    $user=Student::where('id',$token['user_id'])->first();
                    $user['type']='student';
                    return $user;
                    break;
                default:
                    return false;
                    break;
            }
        }
        /*Could not find the token*/
        else return false;
    }

    /*@todo verify user from access_token*/

    public function saveFile($inputFile,$data,$path='uploads',$arrayIndex)
    {
        $uploadedFile = $inputFile;
        $name=$uploadedFile->getClientOriginalName();
        /*Get the file name to be used to save in public directory*/
        /*Upload file to the public directory*/
        $uploadedFile->move(public_path($path),$name);
        $data[$arrayIndex]=asset('/'.$path.'/'.$name);
        return $data;
    }
}
