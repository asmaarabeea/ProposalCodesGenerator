<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    public $StatusCode = 200;
    public $Code = 100;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setCode($code){
        $this->Code = $code;
        return $this;
    }

    public function getStatusCode(){
        return $this->StatusCode;
    }
    public function getCode(){
        return $this->Code;
    }
    public function respondWithError($data,$message){
        return response()->json([
            'status' => false,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }
    public function ValidationError($validation,$message){
        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function($key,$val) {
            return ['key'=>$key,'val'=>implode('|',$val)];
        },array_keys($errorArray),$errorArray),'val','key');

        return $this->setCode(103)->respondWithError($data,implode("\n",array_flatten($errorArray)));
    }

    public function json($status,$msg = '', $data = [], $code = 200)
    {
        echo json_encode( ['status' => $status,'msg' => $msg, 'code' => $code, 'data' => (object)$data]);
    }

}
