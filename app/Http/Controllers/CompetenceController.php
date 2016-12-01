<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Competence;
use App\Member;
use App\User;

class CompetenceController extends Controller
{

    public function getCompetenceList($type, Request $request){

        $userName = $request->get("userName");
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $list = Member::where('user_id', $userId)->get();



        if($type == 'all'){
            $competenceList = Competence::with('creator')->orderBy('createAt', 'desc')->get();
        }else{
            $competenceList = Competence::with('creator')->orderBy('createAt', 'desc')->where('type', $type)->get();
        }

        $result = [];
        foreach($competenceList as $competence){
            $joined = false;
            foreach($list as $member){
                if($member["competence_id"] == $competence["id"]){
                    $joined = true;
                    break;
                }
            }

            if(!$joined){
                $result[] = $competence;
            }

        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "competenceList" => $result
            ]
        ]);
    }

    public function getParticipatedList($userName, $type){

        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $competenceList = [];
        if( $type == "create" ){
            $competenceList = Competence::with('creator')->where('user_id', $userId)->orderBy('createAt', 'desc')->get();
        }else{
            $list = Member::where('user_id', $userId)->get();
            $index = 0;
            foreach($list as $competence){
                $competenceList[$index++] = Competence::with('creator')->where('id', $competence["competence_id"])->get()->first();
            }
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "competenceList" => $competenceList
            ]
        ]);
    }

    public function getCompetenceMembers($competenceId){
        $memberList = Member::where('competence_id', $competenceId)->get();

        $index = 0;
        $infoList = [];
        foreach($memberList as $member){
            $memberInfo = User::where('id', $member["user_id"])->first();

            $infoList[$index++] = ["userId"=>$memberInfo["id"], "userAvatarUrl"=>$memberInfo["avatarUrl"],
                         "userName"=>$memberInfo["userName"], "userRank"=>$member["rank"]];
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "members" => $infoList
            ]
        ]);
    }

    public function joinCompetence($userName, $competenceId){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $tempList = Member::where('user_id', $userId)->where('competence_id', $competenceId)->get();
        if( sizeof($tempList) ){
            return response()->json([
                "state" => false,
                "msg" => "Have Joined"
            ]);
        }else{
            $member = new Member();
            $member["user_id"] = $userId;
            $member["competence_id"] = $competenceId;
            $member->save();
            return response()->json([
                "state" => true,
                "msg" => ""
            ]);
        }
    }

    public function createCompetence($userName, Request $request){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $createAt = date("Y-m-d h:i");

        $competence = new Competence();
        $competence["title"] = $request->get("title");
        $competence["content"] = $request->get("content");
        $competence["bgUrl"] = $request->get("bgUrl");
        $competence["type"] = $request->get("type");
        $competence["bounces"] = $request->get("bounces");
        $competence["startTime"] = $request->get("startTime");
        $competence["endTime"] = $request->get("endTime");
        $competence["tags"] = $request->get("tags");
        $competence["createAt"] = $createAt;
        $competence["user_id"] = $userId;
        $competence->save();

        return response()->json([
            "state" => true,
            "msg" => "",
        ]);


    }

    public function editCompetence($userName, $competenceId, Request $request){
        Competence::where('id', $competenceId)->update([
            "title" => $request->get("title"),
            "content" => $request->get("content"),
            "bgUrl" => $request->get("bgUrl"),
            "type" => $request->get("type"),
            "bounces" => $request->get("bounces"),
            "startTime" => $request->get("startTime"),
            "endTime" => $request->get("endTime"),
        ]);

        return response()->json([
            "state"=>true,
            "msg"=>""
        ]);
    }

    public function exitCompetence($userName, $competenceId){
        $userInfo = User::where('userName', $userName)->get()->first();
        $userId = $userInfo["id"];

        $tempList = Member::where('user_id', $userId)->where('competence_id', $competenceId)->first();
        if( $tempList == null ){
            return response()->json([
                "state" => false,
                "msg" => "Failed To Delete"
            ]);
        }else{
            Member::destroy($tempList["id"]);
            return response()->json([
                "state" => true,
                "msg" => "",
            ]);
        }
    }

    public function deleteCompetence($userName, $competenceId){
        Competence::destroy($competenceId);
        return response()->json([
            "state" => true,
            "msg" => ""
        ]);
    }
}
