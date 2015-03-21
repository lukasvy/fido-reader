<?php

namespace Fido\Users;

use Fido\Core\Api\LoggedInUserApiCtrl as LoggedInUserApiCtrl;

class UserCtrl extends LoggedInUserApiCtrl {

	protected $allowedRoles = [self::ADMIN_ROLE];

	public function get($id = null) {

	}

	/**
	 * Logs in user and returns user information
	 * @return [type] [description]
	 */
	public function logIn() {
		return $this->userInfo();
	}

	/**
	 * Returns user information
	 * @return json
	 */
	public function userInfo() {
		return $this->respond([
			'username'=> $this->user->username,
			'email' => $this->user->email, 
			'role'=> $this->user->role,
			'feeds'=>'2']
		);
	}

}