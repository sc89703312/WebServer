<?php

namespace App\Http\Controllers;

use App\Contract\AdministorInterface;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\User;
use App\Follow;
use App\Post;

class PostController extends Controller
{

    protected $administor;

    public function __construct(AdministorInterface $administor)
    {
        $this->administor = $administor;
    }

    public function getSelfPosts($userName){
        $userInfo = User::where('userName', $userName)->first();
        $userId = $userInfo["id"];

        $postList = Post::with('creator')->where('user_id', $userId)->orderBy('createAt', 'desc')->get();
        return response()->json([
            "state" => true,
            "msg" => "",
            "data" => [
                "postList" => $postList
            ]
        ]);
    }

    public function getFriendPosts($userName, $type){
        $userInfo = User::where('userName', $userName)->first();
        $userId = $userInfo["id"];

        $followingList = Follow::where('follower_id', $userId)->get();
        $followingPosts = [];
        foreach($followingList as $following){
            $posts = Post::with('creator')->where('user_id', $following["following_id"])->orderBy('createAt', 'desc')->get();
            foreach($posts as $post){
                $followingPosts[] = $post;
            }
        }

        return response()->json([
           "state" => true,
            "msg" => "",
            "data" => [
                "postList" => $followingPosts
            ]
        ]);
    }

    public function createPost($userName, Request $request){
        $userInfo = User::where('userName', $userName)->first();
        $userId = $userInfo["id"];

        $post = new Post();
        $createAt = date("Y-m-d h:i");

        $post["user_id"] = $userId;
        $post["title"] = $request->get("title");
        $post["content"] = $request->get("content");
        $post["subContent"] = $request->get("subContent");
        $post["star"] = 0;
        $post["report"] = 0;
        $post["createAt"] = $createAt;

        $post->save();

        return response()->json([
            "state" => true,
            "msg" => "",
        ]);
    }

    public function starPost($postId, $userName){
        $post = Post::where('id', $postId)->first();
        $star = $post["star"];
        Post::where('id', $postId)->update(["star" => ($star+1)]);

        return response()->json([
            "state" => true,
            "msg" => ""
        ]);
    }

    public function reportPost($postId, $userName){
        $post = Post::where('id', $postId)->first();
        $report = $post["report"];
        Post::where('id', $postId)->update(["report" => ($report+1)]);

        return response()->json([
            "state" => true,
            "msg" => ""
        ]);
    }

    public function getReportPost(){
        $posts = Post::with("creator")->where('report', '>=', 5)->orderBy('report','desc')->get();
        return response()->json([
            "state" => true,
            "msg" => "",
            "postList" => $posts
        ]);
    }

    public function test1(){
        return response()->json([
            "state" => true,
            "msg" => "",
            "postList" => $this->administor->getReportPost()
        ]);
    }

    public function test2(){
        return response()->json([
            "state" => true,
            "data" => $this->administor->getReportUser()
        ]);
    }

    public function getReportUser(){
        $users = DB::select('select user_id from post GROUP BY user_id HAVING COUNT(*) >=2');
        $result = [];

        foreach($users as $user){
            $result[] = User::find((int)$user->user_id);
        }

        return response()->json([
            "state" => true,
            "data" => $result
        ]);
    }
}
