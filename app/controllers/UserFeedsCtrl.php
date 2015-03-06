<?php


class UserFeedsCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$id = intval($id);
		if (Auth::check() && $id) {
			$user = Auth::user();
			$feed = Feed::whereActive(true)
					->whereId($id)
					->first();
			$user_feed = User_feed::whereActive(true)
						 ->whereUser_id($user->id)
						 ->whereFeed_id($feed->id)
						 ->first();
			if ($feed && $user_feed) {
				$feed = new LvResponse(array('id'=>$feed->id,'url'=>$feed->url));
				return $feed->respond();
			}
		}
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
			Cache::forget('tickinfo'.$user->id);
            Cache::forget('userinfo'.$user->id);
		}
		return 1;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id) 
		{	
			$feed = Feed::whereActive(true)
					->whereId($id)
					->first();
			if (Auth::check() && $feed) {
				$user = Auth::user();
				$user_feed = User_feed::whereActive(true)
						     ->whereFeed_id($feed->id)
							 ->whereUser_id($user->id)
							 ->first();
				if ($user_feed) {
					$user_feed->active = false;
					$user_feed->save();
                    Cache::forget('userinfo'.$user->id);
				}
			}	
		}
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}