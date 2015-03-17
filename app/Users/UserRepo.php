<?php 

namespace app\Users;

class UserRepo {

	private $user;

	public function __construct () {
		$this->user = \App::make('\app\Users\User');
	}

	public function getUserWithId($id) {
		return $this->user->find($id);
	}
}