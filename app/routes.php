<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',  array('as' => 'home',function()
{
	return View::make('app');
}));

Route::controller('search', 'SearchCtrl');
Route::controller('unique/check/{id?}', 'UniqueCheckCtrl');
Route::controller('article/{id?}', 'ArticleCtrl');
Route::controller('feed', 'FeedsCtrl');
Route::any('user/articles/popular','ArticleCtrl@popular');

Route::group(array('before' => 'authAdmin'), function(){
	Route::controller('admin/feeds/{id?}', 'AdminFeedsCtrl');
	Route::controller('admin/tags/{id?}', 'TagsCtrl');
	Route::controller('admin/users/{id?}', 'UsersCtrl');
	Route::controller('admin/statistics/{id?}', 'AdminStatisticsCtrl');
});

Route::group(array('before' => 'authUser'), function(){
	Route::controller('user/feed/{id?}', 'UserFeedsCtrl');
	Route::any('user/articles/unread','UsersCtrl@unread');
	Route::get('user/profile', function()
    {
        // Has Auth Filter
    });
    	
	Route::post('/logout', function(){
		$user = Auth::user();
		if ($user) {
		    $access_log = new AccessLog();
		    $access_log->user_id = $user->id;
		    $access_log->type = 2; //logout
		    $access_log->ip = Request::getClientIp();
		    $access_log->save();
		}
		Auth::logout();
	});

});

Route::any('/checkuser', function(){
	if (Auth::check()) {
	    $user = Auth::user();
	    $feeds = 0;
	    if ($user){
	    	$feeds = 0;
	    	$user_feeds = false;
	    	$user_feeds = Feed::get_user_feeds($user);
	    	$allUnread = 0;
	    	if ($user_feeds){
		    	$feeds = $user_feeds;
		    	foreach ($feeds as $feed) {
			    	if ($feed['unread']) {
				    	$allUnread += $feed['unread'];
			    	}
		    	}
	    	}
		    $response = new LvResponse(array('user' => 
    			array('username' => $user->username,
    				  'email'	 => $user->email,
    				  'role'  	 => $user->role
    				  
    			),
    			'feeds' => $feeds,
    			'allUnread' => $allUnread
    			));
    		return $response->respond();
	    }
	}
	$response = new LvResponse(array());
	return $response->respond();
});



Route::any('/test', function(){
});

Route::post('/login', function(){
	$res = false;
	$request = new LvRequest();
	$credentials = array(
            "username" => $request->get("username"),
            "password" => $request->get("password")
        );
    if (Auth::attempt($credentials))
    {
    	$feeds = 0;
    	$user = Auth::user();
    	Cache::put($user->username.$user->email, new DateTime(),1);
    	$user_feeds = Feed::get_user_feeds($user);
    	if ($user_feeds){
	    	$feeds = $user_feeds;
    	}
	if ($user) {
            $access_log = new AccessLog();
            $access_log->user_id = $user->id;
            $access_log->type = 1; //login
            $access_log->ip = Request::getClientIp();
            $access_log->save();
        }

    	$res = new LvResponse(array('user' => 
    			array('username' => Auth::user()->username,
    				  'email'	 => Auth::user()->email,
    				  'role'  	 => Auth::user()->role
    				  
    			),
    			'feeds' => $feeds
    			));
    	return $res->respond();
    }
    // test response 
    if (!$res){
    	$res = new LvResponse(array());
    }
    return $res->respond();
});

Route::get('/login', function(){
	return Redirect::to('/');
});

// Filters

Route::filter('authAdmin', function($route, $request){
	if (!Auth::check()){
		return 0;
	}
	$user = Auth::user();
	if ($user && !$user->role == 'admin'){
		Auth::logout();
		return 0;
	}
});

Route::filter('authUser',function($route, $request){
	if (!Auth::check()){
		return 0;
	}
	$user = Auth::user();
	$lastActive = Cache::get($user->username.$user->email);
	if (!$lastActive) {
		Auth::logout();
		return 0;
	}
	Cache::put($user->username.$user->email, new DateTime(),10);

});