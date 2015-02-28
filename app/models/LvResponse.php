<?php

class LvResponse extends BaseController{

	private $response;
	
	/**
	 * Constructor
	 * @param $array
	 */
	public function __construct($array = null) {
		$this->setResponse($array);
	}

	/**
	 * Sets response
	 * @param $array
	 * @return  self
	 */
	public function setResponse ($array = null) {
		if ($array && is_array($array)) {
			$this->response = $array;
			return $this;
		} else {
			$this->response = array($array);
			return $this;
		}
	}
	
	/**
	 * Returns json response
	 * @return json
	 */
	public function respond () {
		return json_encode($this->response);
	}

	/**
	 * Sets response for login/tick, giving information about 
	 * users feeds and unread articles
	 * @param User $user
	 * @return self
	 */
	public function setAuthResponse ($user) {
		$userFeeds = $user->getUserFeedArticles();
	    $all = 0;
	    $index = 0;
	    foreach ($userFeeds as $key => $feed) {
	    	// Set limit of max unread articles
	    	if ($index > 100) {
	    		break;
	    	}
	    	$all += $feed->unread;
	    	$index++;
	    }
		$this->setResponse(
    		array('user' => 
    			array('username' => $user->username,
    				  'email'	 => $user->email,
    				  'role'  	 => $user->role
    				  
    			),
    			'feeds' => $userFeeds,
    			'allUnread' => $all
	    ));

	    return $this;
	}
	
}