<?php 

namespace app\Core\Api;

use app\Users\UserRepo as UserRepo;
use app\Users\Exceptions\UserNotLoggedInException as UserNotLoggedInException;

class LoggedInUserApiCtrl extends ApiCtrl {

	protected $user;
	protected $userRepo;

	public function __construct(UserRepo $userRepo) {
		$this->userRepo  = $userRepo;
		$this->user  	 = $this->loginUser();
	}

	public function logoutUser () {
		\Auth::logout();
		\Event::fire('user.logout');
	}

	private function loginUser() {
		if (!\Auth::check()) {
			if (\Auth::attempt([
					'username' => \Request::get('username'),
					'password' => \Request::get('password')
				])
			) {
				$user = $this->userRepo->getUserWithId(\Auth::user()->id);

				\Event::fire('user.login', ['user' => $user]);

				return $user;
			} else {
				throw new UserNotLoggedInException();
			}
		} else {
			return $this->userRepo->getUserWithId(\Auth::user()->id);
		}
	}

}