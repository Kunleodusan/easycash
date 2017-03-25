<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function success(Request $request)
    {
        $this->successData('message','Successful Test');
        $this->successData('request',$request->all());
        return $this->response();
    }
    public function test(Request $request)
    {
        $this->successData('message','Successful Test');
        $this->successData('request',$request->all());
        return $this->response();
    }

    public function error()
    {
        $this->errorMsg('something must have gone wrong');
        return $this->response();
    }
}
