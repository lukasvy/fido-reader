<?php


class AdminCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$offset = intval(Input::get('offset'));
		$id = intval($id);
		$page = intval(Input::get('page'));
		$skip = ($page * $offset) - $offset;
		$sort = (Input::get('order'));
		$sort = json_decode($sort,true);
		$filter = (Input::get('filter'));
		$filter = json_decode($filter,true);
		$send_feeds = Feed::get_feeds($id,$offset,$skip,$sort,$filter);
		if ($send_feeds && !$id) {
			$response = new LvResponse(
				array(
				'total' => $send_feeds[1],
				'feeds' => $send_feeds[0]
				)
			);
			return $response->respond();
		} else if ($send_feeds && $id) {
			$feed = new LvResponse($send_feeds[0]);
			return $feed->respond();
		}	else {
			return 0;
		}

	}
	
	public function postIndex() 
	{
		$inputUrl = Input::get('url');
		$id = intval(Input::get('id'));
		$inputTags = Input::get('tags');
		Feed::add_edit_feed($id,$inputUrl,$inputTags);
		return 1;	
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