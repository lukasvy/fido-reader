<?php


class UsersCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$id = intval($id);
		if ($id) {
			$user = array();
			$user = User::whereId($id)
			->select(array('id','first_name','last_name', 'username','email','role','active'))
			->first();
			if ($user) {
				$user = $user->toArray();
			}
			// get user tags 
			$tags = User::get_user_tags($id);
			$user['tags'] = $tags;
			$response = new LvResponse($user);
			return $response->respond();
		}
		$offset = intval(Input::get('offset'));
		$page = intval(Input::get('page'));
		$skip = ($page * $offset) - $offset;
		$sort = (Input::get('order'));
		$sort = json_decode($sort,true);
		$filter = (Input::get('filter'));
		$filter = json_decode($filter,true);
		$send = User::get_users($id,$offset,$skip,$sort,$filter);
		if ($send && !$id) {
			$response = new LvResponse(
				array(
				'total' => $send[1],
				'data' => $send[0],
				)
			);
			return $response->respond();
		} else if ($send && $id) {
			$response = new LvResponse($send[0]);
			return $response->respond();
		}	else {
			return 0;
		}
		

	}
	
	public function postIndex($id = NULL) 
	{
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
		if ($id !=1) {
			User::save_user($first_name,$last_name,$id,$role,$email,$password,$username);
		}
		
		return 1;	
	}
	
	public function unread() 
	{
		$request = new LvRequest();
		$page = $request->get('page');
		$offset = $request->get('offset');
		$search_id = $request->get('search_id');
		$user = Auth::user();
		if ($user) {
		    $user_articles = User::get_unread_articles($user->id,$offset,$page,$search_id);
		} else {
		    $user_articles = array();
		}
		$response = new LvResponse($user_articles);
		return $response->respond();	
	}
	
	public function putIndex($id=NULL) 
	{
		
		return 1;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		if ($id && $id != 1) 
		{
			$user = User::find($id);
			if ($user->active) {
				$user->active = false;
				$user->save(); 
			} else {
				$user->active = true;
				$user->save();
			}
		}
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}