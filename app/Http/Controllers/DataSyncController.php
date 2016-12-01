<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DailyData;
use App\SegmentData;

class DataSyncController extends Controller
{
    //

    public function dataSync(Request $request){


        $dailyData = new DailyData();
        $createAt = date("Y-m-d");

        $dailyData["date"] = $createAt;
        $dailyData["steps"] = $request["steps"];
        $dailyData["distance"] = $request["distance"];
        $dailyData["calorie"] = $request["calorie"];
        $dailyData["user_id"] = $request["user_id"];
        $dailyData->save();

        $segmentList = explode(',',$request["segmentList"]);
        $index = 0;
        foreach($segmentList as $segmentItem){
            $temp = new SegmentData();
            $temp["date_id"] = $dailyData["id"];
            $temp["segment"] = $index;
            $temp["steps"] = $segmentItem;

            $temp->save();

            $index++;
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => $dailyData["id"]
        ]);

    }
}
