<?php

class LoginController extends \BaseController {

	private $request;
	private $response;
	private $feed;

	public function __construct(AccessRepo $access, LvRequest $request, LvResponse $response, Feed $feed) {
		$this->response  = $response;
		$this->access  	 = $access;
		$this->request 	 = $request;
		$this->feed 	 = $feed;
	}

	public function Login () {
		$res = false;

		$credentials = array(
            "username" => $this->request->get("username"),
            "password" => $this->request->get("password")
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
				$this->access->logUserAccess($user->id, 1, $this->request->getClientIp());
		    }

		    return $this->response->sendUserAuthResponse();
	    }
	    // test response 
	    if (!$res){
	    	$res = $this->response->setResponse(array());
	    }
	    return $res->respond();
	}
}