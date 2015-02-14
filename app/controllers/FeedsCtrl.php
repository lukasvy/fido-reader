<?php


class FeedsCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$request = new LvRequest();
		$id = intval($request->get('id'));
		$page = intval($request->get('page'));
		$offset = intval($request->get('offset'));
		$user = intval($request->get('user'));
		if (!$page) {
			$page = 0;
		}
		if ($id) {
			$articles = Feed::get_feed_articles($id, $page, $offset);
			$response = new LvResponse($articles);
			return $response->respond();
		}
		return 0;
	}
	
	public function postIndex() 
	{
		$request = new LvRequest();
		$id = intval($request->get('id'));
		$page = intval($request->get('page'));
		$offset = intval($request->get('offset'));
		$user = intval($request->get('user'));
		
		if (!$page) {
			$page = 0;
		}
		$user = false;
		$userid = false;
		if (Auth::check()) {
			$user = Auth::user();
			$userid = $user->id;
		}
		if ($id) {
			$articles = Feed::get_feed_articles($id, $page, $offset,$userid);
			$response = new LvResponse($articles);
			return $response->respond();
		}
		return 0;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id) 
		{
			Feed::remove($id);	
		}
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}