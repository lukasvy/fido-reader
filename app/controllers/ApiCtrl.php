<?php

// DEPRECATED

class ApiCtrl extends \BaseController{
	
	private $request;

	public function __construct (LvRequest $request) {
		$this->request = $request;
	}

	public function respond ($data) {
		
	}


}