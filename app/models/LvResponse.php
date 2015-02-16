<?php

class LvResponse {

	private $response;
	
	public function __construct($array = null) {
		$this->setResponse($array);
	}

	public function setResponse ($array = null) {
		if ($array && is_array($array)) {
			$this->response = $array;
			return $this;
		} else {
			$this->response = array($array);
			return $this;
		}
	}
	
	public function respond () {
		return json_encode($this->response);
	}

	public function setAuthResponse ($user, $feeds = array(), $allUnread = 0) {
		$this->setResponse(
    		array('user' => 
    			array('username' => $user->username,
    				  'email'	 => $user->email,
    				  'role'  	 => $user->role
    				  
    			),
    			'feeds' => $feeds,
    			'allUnread' => $allUnread
	    ));

	    return $this;
	}
	
}