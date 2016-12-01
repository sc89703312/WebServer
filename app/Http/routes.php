<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get("setSession", function(Request $request){

});

Route::get("testSession", function(Request $request){
    return $request->cookie("laravel_session");
});

/*将所有的路由都加上跨域处理的中间件*/
Route::group(['middleware'=>'cross'], function() {


    /*
     | 匹配 /users/{userName}/
     | 与用户个人信息或者操作相关
     */
    Route::group(['prefix' => 'users/{userName}'], function() {

        /*
         | --------------------------------
         | GET  /users/{userName}/info
         | 获取用户名为 userName 的用户个人信息
         | --------------------------------
        */
        Route::get('info', "UserController@getUserBasicInfo");

        /*
         | --------------------------------
         | PUT  /users/{userName}/info
         | 修改用户名为 userName 的用户个人信息
         | --------------------------------
        */
        Route::post('info/modify', "UserController@modifyUserBasicInfo");

        /*
         | --------------------------------
         | GET  /users/{userName}/signIn
         | 获取用户名为 userName 的消息通知数
         | --------------------------------
        */
        Route::get('signIn', "UserController@getUserSignIn");

        /*
         | --------------------------------
         | POST  /users/{userName}/competences/{competenceId}
         | 用户名为 userName 加入 competenceId 的竞赛活动中
         | --------------------------------
        */
        Route::post('competences/{competenceId}', "CompetenceController@joinCompetence");

        /*
         | --------------------------------
         | POST  /users/{userName}/competences
         | 用户名为 userName 新创建了竞赛活动
         | --------------------------------
        */
        Route::post('competences', "CompetenceController@createCompetence");

        /*
         | --------------------------------
         | PUT  /users/{userName}/competences/{competenceId}
         | 用户名为 userName 编辑 competenceId 的竞赛活动
         | --------------------------------
        */
        Route::put('competences/{competenceId}', "CompetenceController@editCompetence");

        /*
         | --------------------------------
         | DELETE  /users/{userName}/competences/{competenceId}
         | 用户名为 userName 删除 competenceId 的竞赛活动
         | --------------------------------
        */
        Route::post('competences/{competenceId}/exit', "CompetenceController@exitCompetence");

        Route::post('competences/{competenceId}/delete', "CompetenceController@deleteCompetence");
        /*
         | --------------------------------
         | GET  /users/{userName}/posts
         | 获取用户名为 userName 的个人动态信息
         | --------------------------------
        */
        Route::get('posts', "PostController@getSelfPosts");

        /*
         | --------------------------------
         | POST  /users/{userName}/posts
         | 用户 userName 发送新动态
         | --------------------------------
        */
        Route::post('posts', "PostController@createPost");

        /*
         | --------------------------------
         | GET  /users/{userName}/following
         | 获取用户名为 userName 的关注列表
         | --------------------------------
        */
        Route::get('following', "FriendController@getFollowing");

        /*
         | --------------------------------
         | GET  /users/{userName}/followers
         | 获取用户名为 userName 的粉丝列表
         | --------------------------------
        */
        Route::get('followers', "FriendController@getFollower");

        /*
         | --------------------------------
         | POST  /users/{userName}/follow/{followingName}
         | 用户 userName 关注了用户 followingName
         | --------------------------------
        */
        Route::post('follow/{followingName}', "FriendController@follow");

        /*
         | --------------------------------
         | POST  /users/{userName}/unfollow/{followingName}
         | 用户 userName 取关了用户 followingName
         | --------------------------------
        */
        Route::post('unfollow/{followingName}', "FriendController@unfollow");

        /*
         | --------------------------------
         | GET  /users/{userName}/healthInfo
         | 获取用户userName的个人健康信息
         | --------------------------------
        */
        Route::get('healthInfo', "SportController@getHealthInfo");

        /*
         | --------------------------------
         | GET  /users/{userName}/sportTotal
         | 获取用户userName的运动总况
         | --------------------------------
        */
        Route::get('sportTotal', "SportController@getSportTotal");

        /*
         | --------------------------------
         | GET  /users/{userName}/weekSteps
         | 获取用户userName的近一周运动步数
         | --------------------------------
        */
        Route::get('weekSteps', "SportController@getWeeklySteps");

        /*
         | --------------------------------
         | GET  /users/{userName}/weekIntensity
         | 获取用户userName的近一周运动强度
         | --------------------------------
        */
        Route::get('weekIntensity', "SportController@getWeeklyIntensity");

        /*
         | --------------------------------
         | GET  /users/{userName}/dailySport
         | 获取用户userName的某日运动数据
         | --------------------------------
        */
        Route::get('dailySport', "SportController@getDailyTotal");

        /*
         | --------------------------------
         | GET  /users/{userName}/dailySteps
         | 获取用户userName的某日步数
         | --------------------------------
        */
        Route::get('dailySteps', "SportController@getDailySteps");
    });

    /*
     | 匹配 /competences
     | 与竞赛内容相关
    */
    Route::group(['prefix' => 'competences'], function() {

        /*
         | --------------------------------
         | GET  /competences/{type}
         | 获取类型为type的竞赛活动列表
         | --------------------------------
        */
        Route::get('{type}', "CompetenceController@getCompetenceList");

        /*
         | --------------------------------
         | GET  /usr/competences/{userName}/{type}
         | 获取用户 userName 参与的类型为type的竞赛列表
         | --------------------------------
        */
        Route::get('/usr/{userName}/{type}', "CompetenceController@getParticipatedList");

        /*
         | --------------------------------
         | GET  /competences/{competenceId}/members
         | 获取competenceId的竞赛活动的参与成员列表
         | --------------------------------
        */
        Route::get('{competenceId}/members', "CompetenceController@getCompetenceMembers");

        /*
         | --------------------------------
         | GET  /competences/{competenceId}/result
         | 获取competenceId的竞赛活动的结果
         | --------------------------------
        */
        Route::get('{competenceId}/result', "CompetenceController@getCompetenceMembers");
    });

    /*
     | 匹配 /posts/
     | 与动态内容相关
     */
    Route::group(['prefix' => 'posts'], function() {

        /*
         | --------------------------------
         | GET  /posts/{userName}/{type}
         | 获取用户名为 userName 的用户的type类型的动态信息
         | --------------------------------
        */
        Route::get('{userName}/{type}', "PostController@getFriendPosts");

        /*
         | --------------------------------
         | POST  /posts/{postId}/star/{userName}
         | 用户 userName 点赞了 postId
         | --------------------------------
        */
        Route::post('{postId}/star/{userName}', "PostController@starPost");

        /*
         | --------------------------------
         | POST  /posts/{postId}/report/{userName}
         | 用户 userName 举报了 postId
         | --------------------------------
        */
        Route::post('{postId}/report/{userName}', "PostController@reportPost");
    });

    /*
     | 其他一些
     | 搜索|登陆|注册
     */
    Route::post("/auth", "UserController@login");

    Route::post('/user', "UserController@register");

    Route::get('/friend/search', "FriendController@getSearchResult");

    Route::get('/admin/posts', "PostController@test1");

    Route::get('/admin/users', "PostController@test2");

    /*
     * 用于做运动数据的插入和更新
     */
    Route::post('/sync/data', "DataSyncController@dataSync");
});