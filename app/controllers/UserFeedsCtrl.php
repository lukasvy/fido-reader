<?php


class UserFeedsCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		
		return 0;
	}
	
	public function postIndex() 
	{
		$request = new LvRequest();
		$url = $request->get('url');
		if (Auth::check()) {
			$user = Auth::user();
			$feed = Feed::whereActive(true)
					->whereUrl($url)
					->first();
			if (!$feed) {
				$feed = Feed::add_edit_feed(NULL,$url,NULL);
			}
			$user_feed = User_feed::whereActive(true)
						->whereUser_id($user->id)
						->whereFeed_id($feed->id)
						->first();
			if ($feed && !$user_feed) {
				$user_feed = new User_feed();
				$user_feed->user_id = $user->id;
				$user_feed->feed_id = $feed->id;
				$user_feed->save();
			}
		}
		return 0;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id) 
		{
			if (Auth::check()) {
				$user = Auth::user();
				
			}	
		}
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}