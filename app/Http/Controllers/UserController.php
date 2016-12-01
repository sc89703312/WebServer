<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{

    public function login(Request $request){
        $username = $request->get("username");
        $password = $request->get("password");
        $result = "";

        $userInfo = User::where('userName', $username)->get()->first();

        if( $password == $userInfo["id"] ){
            $result["state"] = true;
            $result["msg"]="";
            return response()->json($result);
        }else{
            $result["state"] = false;
            $result["msg"] = "Password Error";
            return response()->json($result);
        }
    }

    public function register(Request $request){

    }

    public function getUserBasicInfo($userName){
        $result = "";
        $result["state"] = true;
        $result["msg"]="";
        $result["data"]= User::where('userName', $userName)->get()->first();

        return response()->json($result);
    }

    public function modifyUserBasicInfo($userName, Request $request){

        User::where('userName', $userName)->update([
            "birthDate" => $request->get("birthDate"),
            "content" => $request->get("content"),
            "job" => $request->get("job"),
            "address" => $request->get("address"),
            "sex" => $request->get("sex"),
            "height" => $request->get("height"),
            "weight" => $request->get("weight"),
        ]);

        return response()->json([
            "state" => true,
            "msg" => ""
        ]);
    }

    public function getUserSignIn($userName){
        return response()->json(
            [
                "state" => true,
                "msg" => "",
                "data"=> [
                    "signIn" => 5
                ]
            ]
        );
    }
}
