<?php 

namespace Fido\Users;

class UserRepo {

	private $user;

	public function __construct () {
		$this->user = \App::make('Fido\Users\User');
	}

	public function getUserWithId($id) {
		return $this->user->find($id);
	}
}