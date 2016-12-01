<?php

namespace App\Http\Controllers;

use ClassesWithParents\F;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Follow;

class FriendController extends Controller
{
    public function getFollowing($userName){

        $userInfo = User::where('userName', $userName)->first();
        $userId = $userInfo["id"];

        $followingList = Follow::where('follower_id', $userId)->get();
        $follow = [];
        $index = 0;
        foreach($followingList as $following){
            $followingInfo = User::where('id', $following["following_id"])->first();
            $follow[$index++] = ["userId"=>$followingInfo["id"],
                                 "userName"=>$followingInfo["userName"],
                                 "avatarUrl"=>$followingInfo["avatarUrl"]];
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "followList" => $follow
            ]
        ]);
    }

    public function getFollower($userName){
        $userInfo = User::where('userName', $userName)->first();
        $userId = $userInfo["id"];

        $followerList = Follow::where('following_id', $userId)->get();
        $follow = [];
        $index = 0;
        foreach($followerList as $follower){
            $followerInfo = User::where('id', $follower["follower_id"])->first();
            $follow[$index++] = ["userId"=>$followerInfo["id"],
                "userName"=>$followerInfo["userName"],
                "avatarUrl"=>$followerInfo["avatarUrl"]];
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "followList" => $follow
            ]
        ]);
    }

    public function getSearchResult(Request $request){
        $keywords = $request->get("keywords");
        $userList = User::where('userName', 'like', '%'.$keywords.'%')->get();
        $followList = [];
        $index = 0;

        foreach($userList as $user){
            $followList[$index++] = [
                "userId" => $user["id"],
                "userName" => $user["userName"],
                "avatarUrl" => $user["avatarUrl"]
            ];
        }

        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "followList" => $followList
            ]
        ]);

    }

    public function follow($userName, $followingName){
        $followerInfo = User::where('userName', $userName)->first();
        $followerId = $followerInfo["id"];
        $followingInfo = User::where('userName', $followingName)->first();
        $followingId = $followingInfo["id"];

        $tempList = Follow::where('follower_id', $followerId)->where('following_id', $followingId)->get();
        if( sizeof($tempList)==0 ){
            $follow = new Follow();
            $follow["following_id"] = $followingId;
            $follow["follower_id"] = $followerId;
            $follow->save();
            return response()->json([
                "state" => true,
                "msg" => ""
            ]);
        }else{
            return response()->json([
                "state" => false,
                "msg" => "You have followed the user"
            ]);
        }

    }

    public function unfollow($userName, $followingName){
        $followerInfo = User::where('userName', $userName)->first();
        $followerId = $followerInfo["id"];
        $followingInfo = User::where('userName', $followingName)->first();
        $followingId = $followingInfo["id"];

        $tempList = Follow::where('follower_id', $followerId)->where('following_id', $followingId)->get();
        if( sizeof($tempList)==1 ){
            Follow::where('follower_id', $followerId)->where('following_id', $followingId)->delete();
            return response()->json([
                "state" => true,
                "msg" => ""
            ]);
        }else{
            return response()->json([
                "state" => false,
                "msg" => "You didn't follow the user"
            ]);
        }
    }
}
