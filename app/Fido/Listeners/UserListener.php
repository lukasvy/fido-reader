<?php 

namespace Fido\Listeners;

use \Fido\Core\Eventing\EventListener;
use \Fido\AccessLog\AccessLogRepo;

class UserListener extends EventListener {

	private $accessLog;

	public function __construct(AccessLogRepo $accesLog) {
		$this->accessLog = $accesLog;
	}

	public function whenUserWasLoggedIn($user) {
		\Cache::put($user->username.$user->email, new \DateTime(), \Config::get('app.timeout'));
		$this->accessLog->logUser($user);
	}

	public function whenUserWasLoggedOut($user) {
		
	}

}