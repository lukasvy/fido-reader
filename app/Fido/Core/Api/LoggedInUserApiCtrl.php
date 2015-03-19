<?php 

namespace Fido\Core\Api;

use Fido\Users\UserRepo as UserRepo;
use Fido\Users\Exceptions\UserNotLoggedInException as UserNotLoggedInException;
use Fido\Users\Events\UserWasLoggedIn;
use Fido\Users\Events\UserWasLoggedOut;

class LoggedInUserApiCtrl extends ApiCtrl {

	use \Fido\Core\Eventing\EventTrait;

	// User that is loged in
	protected $user;
	// User repo to use to manipulate User
	protected $userRepo;
	

	public function __construct(UserRepo $userRepo) {
		$this->userRepo  = $userRepo;
		$this->user  	 = $this->loginUser();
	}

	public function logoutUser () {
		if (\Auth::check()) {
			\Auth::logout();
			$this->fire(new UserWasLoggedOut($this->user));
			$this->user = null;
		}
	}

	private function loginUser() {
		if (!\Auth::check()) {
			if (\Auth::attempt([
					'username' => \Request::get('username'),
					'password' => \Request::get('password')
				])
			) {
				$this->user = $this->userRepo->getUserWithId(\Auth::user()->id);

				$this->fire(new UserWasLoggedIn($this->user));

				return $this->user;
			} else {
				throw new UserNotLoggedInException();
			}
		} else {
			return $this->userRepo->getUserWithId(\Auth::user()->id);
		}
	}

}