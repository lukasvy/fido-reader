<?php


class AdminFeedsCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$req = new LvRequest();
		$offset = intval($req->get('offset'));
		$id = intval($id);
		$page = intval($req->get('page'));
		$skip = ($page * $offset) - $offset;
		$sort = ($req->get('order'));
		$sort = json_decode($sort,true);
		$filter = ($req->get('filter'));
		$filter = json_decode($filter,true);
		$send_feeds = Feed::get_feeds($id,$offset,$skip,$sort,$filter);
		if ($send_feeds && !$id) {
			$response = new LvResponse(
				array(
				'total' => $send_feeds[1],
				'data' => $send_feeds[0]
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
		$req = new LvRequest();
		$inputUrl = $req->get('url');
		$id = intval($req->get('id'));
		$inputTags = $req->get('tags');
		Feed::add_edit_feed($id,$inputUrl,$inputTags);
		if (Cache::has('tickinfo')) {
                    Cache::forget('tickinfo');
                }
		return 1;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id) 
		{
			Feed::remove($id);	
		}
		if (Cache::has('tickinfo')) {
                    Cache::forget('tickinfo');
                }

	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}