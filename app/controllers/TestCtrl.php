<?php 

use Illuminate\Http\Request as Request;

class TestCtrl extends BaseController {
	
	public function __construct () {
		
		var_dump(Auth::user());
		
		
	}

	public function test () {

	}
}