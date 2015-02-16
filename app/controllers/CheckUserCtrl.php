<?php

class CheckUserCtrl extends \BaseController {

	private $user;
	private $response;
	private $feedRepo;

	public function __construct (LvResponse $response, FeedRepo $feedRepo) {

		$this->response = $response;
		$this->feedRepo = $feedRepo;

		if (Auth::check()) {
			$this->user = Auth::user();
		}
	}

	public function checkUser () {
		$res = false;

		if ($this->user) {
		    $feeds 	= $this->feedRepo
		    			   ->getUserFeeds($this->user);
			$res 	= $this->response
						   ->setAuthResponse($this->user, $feeds['feeds'], $feeds['allUnread']);
		}

		if (!$res) {
			$res = $this->response
						->setResponse(array());
		}
		return $res->respond();
	}

}