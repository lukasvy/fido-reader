<?php 

class LvRequest {
	
	var $request;
	
	public function __construct () {
		if ($get = Input::all()) {
			// check for specific input
			$this->request = $get;
			return true;
		} else {
			return false;
		}
		// filter queries here
		
	}
	
	public function get ($specific_input = null) {
		if ($specific_input) 
		{
			if ($this->request && array_key_exists($specific_input, $this->request)) {
				return $this->request[$specific_input];
			}
		} else {
			return $this->request;
		}
		return false;
	}
	
	private function filter () {
		
	}
	
}