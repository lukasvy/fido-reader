<?php

class CheckUserCtrl extends \BaseController {

	private $user;
	private $response;
	private $feedRepo;

	public function __construct (LvResponse $response, FeedRepo $feedRepo) {

		$this->response = $response;
		$this->feedRepo = $feedRepo;

		if (Auth::check()) {
			$this->user = User::find(Auth::user()->id);
		}
	}

	public function checkUser () {
		$res = false;

		if ($this->user) {
			$res = $this->response->setAuthResponse($this->user);
		}

		if (!$res) {
			$res = $this->response
						->setResponse(array());
		}
		return $res->respond();
	}

}