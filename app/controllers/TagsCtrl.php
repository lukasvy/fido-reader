<?php


class TagsCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$id = intval($id);
		$offset = intval(Input::get('offset'));
		$page = intval(Input::get('page'));
		$skip = ($page * $offset) - $offset;
		$sort = (Input::get('order'));
		$sort = json_decode($sort,true);
		$filter = (Input::get('filter'));
		$filter = json_decode($filter,true);
		$send = Tag::get_tags($id,$offset,$skip,$sort,$filter);
		if ($send && !$id) {
			$response = new LvResponse(
				array(
				'total' => $send[1],
				'data' => $send[0]
				)
			);
			return $response->respond();
		} else if ($send && $id) {
			$response = new LvResponse($send);
			return $response->respond();
		}	else {
			return 0;
		}
		

	}
	
	public function postIndex($id = NULL) 
	{
		/*
$first_name = Input::get('first_name');
		$last_name = Input::get('last_name');
		$id = Input::get('id');
		$role = Input::get('role');
		$email = Input::get('email');
		$password = Input::get('password');
		if (Input::get('password1')) {
			$password = Input::get('password1');
		}
		$username = Input::get('username');
		
		User::save_user($first_name,$last_name,$id,$role,$email,$password,$username);
		
*/		
		$request = new LvRequest();
		$tags = $request->get('tags');
		if ($tags) {
			$tags = preg_replace('/\s/',',',$tags);
			$tags = explode(',',$tags);
			foreach($tags as $tag) {
				Tag::save_tag($tag);
			}
		}
		if (Cache::has('tickinfo')) {
                    Cache::forget('tickinfo');
                }

		return 1;	
	}
	
	public function putIndex($id=NULL) 
	{
		
		return 1;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id) 
		{
			$tag = Tag::find($id);
			if ($tag->active) {
				$tag->active = false;
				$tag->save(); 
			} else {
				$tag->active = true;
				$tag->save();
			}
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