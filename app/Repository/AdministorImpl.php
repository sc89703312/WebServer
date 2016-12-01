<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/1
 * Time: 15:16
 */

namespace App\Repository;


use App\Contract\AdministorInterface;

use App\Post;
use App\User;
use Illuminate\Support\Facades\DB;

class AdministorImpl implements AdministorInterface
{
    public function getReportPost(){
        $posts = Post::with("creator")->where('report', '>=', 5)->orderBy('report','desc')->get();
        return $posts;
    }

    public function getReportUser(){
        $users = DB::select('select user_id from post GROUP BY user_id HAVING COUNT(*) >=2');
        $result = [];

        foreach($users as $user){
            $result[] = User::find((int)$user->user_id);
        }

        return $result;
    }
}