<?php 

namespace app\Users;

class UserListener {

	private $accessLog;

	public function __construct() {
		$this->accessLog = \App::make('AccessLogRepo');
	}

    /**
     * Handle user login events.
     */
    public function onUserLogin($user)
    {
        \Cache::put($user->username.$user->email, new \DateTime(), \Config::get('app.timeout'));
		$this->accessLog->logUser($user);
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('user.login', 'UserListener@onUserLogin');

        $events->listen('user.logout', 'UserListener@onUserLogout');
    }

}