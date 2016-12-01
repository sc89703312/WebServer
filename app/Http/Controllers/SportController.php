<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\DailyData;
use App\SegmentData;

use App\Http\Requests;

class SportController extends Controller
{

    public function getHealthInfo($userName){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];
        $userHeight = $userInfo["height"];
        $userWeight = $userInfo["weight"];

        $data["BMI"] = $userWeight/($userHeight*$userHeight);
        $data["goal"] = ["complete"=>5, "total"=>7];
        $data["kinds"] = [["name"=>"跑步", "value"=> 55],
            ["name"=>"骑行", "value"=> 25],
            ["name"=>"游泳", "value"=> 20]];

        $result["state"] = "true";
        $result["msg"]="";
        $result["data"] = $data;

        return response()->json($result);
    }

    public function getSportTotal($userName){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $dailyDataList = DailyData::where('user_id', $userId)->get();
        $steps = 0;
        $distance = 0;
        $calorie = 0;
        foreach($dailyDataList as $dailyData){
            $steps += $dailyData["steps"];
            $distance += $dailyData["distance"];
            $calorie += $dailyData["calorie"];
        }

        $data = ["steps"=>$steps, "distance"=>$distance, "energy"=>$calorie];
        $result = ["state"=>true, "msg"=>"", "data"=>$data];

        return response()->json($result);
    }

    public function getDailySteps($userName, Request $request){

        $date = $request->get('date');

        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $dailyData = DailyData::where('user_id', $userId)->where('date', $date)->get()->first();
        $id = $dailyData["id"];

        $segmentData = SegmentData::where('date_id', $id)->get();
        $steps = [];
        $index = 0;

        $max_segment = 0;
        $max_steps = 0;

        foreach($segmentData as $data){
            $steps[$index] = $data["steps"];
            if($data["steps"]>=$max_steps){
                $max_steps = $data["steps"];
                $max_segment = $data["segment"];
            }
            $index += 1;
        }

        $description = ($max_segment*2).":00-".(($max_segment+1)*2).":00";

        $result = ["state"=>true, "msg"=>"", "data"=>["steps"=>$steps, "description"=>$description]];
        return response()->json($result);
    }

    public function getDailyTotal($userName, Request $request){

        $date = $request->get('date');

        $levels = ['过少', '适中', '过量'];

        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];
        $dailyData = DailyData::where('user_id', $userId)->where('date', $date)->get()->first();

        $dailySteps = $dailyData["steps"];
        if($dailySteps<9000)
            $levelIndex = 0;
        elseif($dailySteps<12000)
            $levelIndex = 1;
        else
            $levelIndex = 2;



        $data = ["steps"=>$dailyData["steps"], "distance"=>$dailyData["distance"],
                "energy"=>$dailyData["calorie"],"speed"=>round(($dailyData["distance"]/24),2),
                "description"=>$levels[$levelIndex]];

        $result = ["state"=>true, "msg"=>"", "data"=>$data];

        return response()->json($result);
    }

    public function getWeeklySteps($userName){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $dailyDataList = DailyData::where('user_id', $userId)->get();
        $weeklySteps = [];
        $index = 0;
        foreach($dailyDataList as $dailyData){
            $weeklySteps[$index] = $dailyData["steps"];
            $index += 1;
            if($index==7)
                break;
        }

        $result = ["state"=>true, "msg"=>"", "data"=>["steps"=>$weeklySteps]];
        return response()->json($result);
    }

    public function getWeeklyIntensity($userName){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $dailyDataList = DailyData::where('user_id', $userId)->get();
        $dailyId = [];
        $index = 0;
        foreach($dailyDataList as $dailyData){
            $dailyId[$index] = $dailyData["id"];
            $index += 1;
            if($index==7)
                break;
        }


        $weekIntensity = [];
        $index = 0;
        $first = 0;
        $second = 0;
        foreach($dailyId as $id){
            $segmentDataList = SegmentData::where('date_id', $id)->get();
            foreach($segmentDataList as $segmentData){
                $weekIntensity[$index] = [$second, $first, round(($segmentData["steps"]-400)/100)];
                $index += 1;
                $first += 1;
            }
            $first = 0;
            $second += 1;
        }
        $result = ["state"=>true, "msg"=>"", "data"=>["weekIntensity"=>$weekIntensity]];
        return response()->json($result);
    }
}
