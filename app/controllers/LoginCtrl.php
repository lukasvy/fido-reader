<?php

class LoginCtrl extends \BaseController {

	private $request;
	private $response;
	private $feedRepo;
	private $user;

	public function __construct(AccessRepo $access, LvRequest $request, LvResponse $response, FeedRepo $feedRepo) {

		$this->response  = $response;
		$this->access  	 = $access;
		$this->request 	 = $request;
		$this->feedRepo  = $feedRepo;
		
		$credentials = array(
            "username" => $this->request->get("username"),
            "password" => $this->request->get("password")
	    );

	    if (Auth::attempt($credentials)) {
	    	$this->user = Auth::user();
	    }
	}

	public function Login () {
		$res = false;
		
		$credentials = array(
            "username" => $this->request->get("username"),
            "password" => $this->request->get("password")
	    );

	    if ($this->user)
	    {
	    	$feeds = 0;
	    	$user = $this->user;


	    	Cache::put($user->username.$user->email, new DateTime(),1);

	    	$user_feeds = $this->feedRepo
		    			   	   ->getUserFeeds($this->user);

	    	if ($user_feeds){
		    	$feeds = $user_feeds;
	    	}

			if ($user) {
				$this->access->logUserAccess($user->id, 1, $this->request->getClientIp());
		    }

		    $res = $this->response->setAuthResponse($user, $feeds);
	    }
	    // test response 
	    if (!$res){
	    	$res = $this->response->setResponse(array());
	    }
	    return $res->respond();
	}
}