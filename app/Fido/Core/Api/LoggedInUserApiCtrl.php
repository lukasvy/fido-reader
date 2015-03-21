<?php 

namespace Fido\Core\Api;

use Fido\Users\UserRepo as UserRepo;
use Fido\Users\Exceptions\UserNotLoggedInException as UserNotLoggedInException;
use Fido\Users\Events\UserWasLoggedIn;
use Fido\Users\Events\UserWasLoggedOut;
use Fido\Core\Exceptions\NotAllowedException;

class LoggedInUserApiCtrl extends ApiCtrl {

	use \Fido\Core\Eventing\EventTrait;

	// Constant defines roles in the system
	const ADMIN_ROLE = 'admin';
	const USER_ROLE  = 'user';
	const GUEST_ROLE = 'guest';


	// User that is loged in
	protected $user;
	// User repo to use to manipulate User
	protected $userRepo;

	protected $allowedRoles = [self::ADMIN_ROLE];
	

	public function __construct(UserRepo $userRepo) {
		$this->userRepo  = $userRepo;
		$this->user  	 = $this->loginUser();

		// check for permissions for viewing
		if (!$this->hasPermissionToView($this->user)){
			throw new NotAllowedException();
		}
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

	/**
	 * Check if user has permission to view this api
	 * @return boolean             
	 */
	private function hasPermissionToView($user) {
		$result = false;
		if (sizeof($this->allowedRoles) > 0) {
			foreach ($this->allowedRoles as $value) {
				if ($user->role == $value) {
					$result = true;
				}
			}
		} else {
			$result = true;
		}
		return $result;
	}

}