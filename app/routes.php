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

Route::any('/checkuser', 'CheckUserCtrl@checkUser');

Route::any('/test','TestCtrl@test' );

// Route::any('/test', function(){
// 	$test = false;
// 	//Kint::dump(Feed::first()->tags->lists('tag'));
// 	//Debugbar::info(Feed::first()->tags->lists('tag'));
// 	//Kint::dump(User::find(5)->feeds->lists('id'));
// 	//$test = User::find(5)->feeds;
// 	//$test = App::make('FeedRepo');
// 	//$test = $test->getUserFeeds(User::find(5));
// 	#var_dump($test);
// 	//$test = Feed::find(1)->articles->lists('id','title');
// 	//Kint::dump($test);
// 	// $test = User::find(5);
// 	// if ($test) {
// 	// 	$test = $test->tags->lists('id','tag');
// 	// }
// 	// Kint::dump($test);
	
// });

Route::post('/login', 'LoginCtrl@login');

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
