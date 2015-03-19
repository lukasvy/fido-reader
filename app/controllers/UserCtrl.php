<?php

use Fido\Core\Api\LoggedInUserApiCtrl as LoggedInUserApiCtrl;

class UserCtrl extends LoggedInUserApiCtrl {

	public function logIn() {
		return $this->respond([
			'username'=> $this->user->username,
			'email' => $this->user->email, 
			'role'=> $this->user->role,
			'feeds'=>'2']
		);
	}

}