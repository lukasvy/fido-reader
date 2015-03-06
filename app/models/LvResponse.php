<?php

class LvResponse {

	private $response;
	
	public function __construct($array = null) {
		if ($array && is_array($array)) {
			$this->response = $array;
			return true;
		} else {
			$this->response = array($array);
			return true;
		}
		return false;
	}
	
	public function respond () {
		return json_encode($this->response);
	}
	
}