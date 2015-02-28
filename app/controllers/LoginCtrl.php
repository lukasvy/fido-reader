<?php
/**
 * Class deals with logging in users
 * @Lukas Vyslocky 2015
 */
class LoginCtrl extends BaseController {

	private $request;
	private $response;
	private $user;

	public function __construct(AccessRepo $access, LvRequest $request, LvResponse $response) {

		$this->response  = $response;
		$this->access  	 = $access;
		$this->request 	 = $request;
		
		$credentials = array(
            "username" => $this->request->get("username"),
            "password" => $this->request->get("password")
	    );

	    if (Auth::attempt($credentials)) {
	    	$this->user = User::find(
	    		Auth::user()->id);
	    }
	}

	/**
	 * Logs in user and sets response
	 */
	public function Login () {
		$res = false;

	    if ($this->user)
	    {
	    	$user = $this->user;

	    	Cache::put($user->username.$user->email, new DateTime(),1);

			if ($user) {
				$this->access->logUserAccess($user->id, 1, $this->request->getClientIp());
		    }
		    $res = $this->response->setAuthResponse($this->user);
	    }
	    // test response 
	    if (!$res){
	    	$res = $this->response->setResponse(array());
	    }
	    return $res->respond();
	}
}