<?php 

namespace Fido\Listeners;

use Fido\Core\Eventing\EventListener;
use Fido\AccessLog\AccessLogRepo;

class UserListener extends EventListener {

	private $accessLog;

	/**
	 * Injector
	 * @param AccessLogRepo $accesLog
	 */
	public function __construct(AccessLogRepo $accesLog) {
		$this->accessLog = $accesLog;
	}

	/**
	 * Handle user was logged in event
	 * @param  Object $user 
	 */
	public function whenUserWasLoggedIn($user) {
		$cache  = \App::make('cache');
		$config = \App::make('config');
		$cache->put($user->username.$user->email, new \DateTime(), $config->get('app.timeout'));
		$this->accessLog->logUser($user);
	}

	/**
	 * Handle user was logged out event
	 * @param  Object $user 
	 */
	public function whenUserWasLoggedOut($user) {
		
	}

}