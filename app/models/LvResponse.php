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
		return $this;
	}
	
	public function respond () {
		return json_encode($this->response);
	}

	public function sendUserAuthResponse ($feeds = array()) {
		$this->setResponse(
    		array('user' => 
    			array('username' => Auth::user()->username,
    				  'email'	 => Auth::user()->email,
    				  'role'  	 => Auth::user()->role
    				  
    			),
    			'feeds' => $feeds
	    ))->respond();
	}
	
}