<?php

namespace app\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider {

	/**
	 * Register handlers
	 * @return
	 */
    public function register() {
        $this->bindExceptions();
        $this->bindEventListeners();
        $this->bindRepos();
        $this->registerEvents();
        $this->registerExceptionsHandlers();
    }

    public function bindRepos() {
        $this->app->bind('AccessLogRepo','\app\AccessLog\AccessLogRepo');
    }

    /**
     * Bind exceptions to App container
     */
    public function bindExceptions(){
        $this->app->bind('UserNotLoggedInException','\app\Users\Exceptions\UserNotLoggedInException');
    }

    /**
     * Bind event listeners to App container
     */
    public function bindEventListeners(){
        $this->app->bind('UserListener','\app\Users\UserListener');
    }

    /**
     * Global Event handlers
     */
    public function registerEvents () {
    	\Event::subscribe('UserListener');
    }

    /**
     * Global Exception handlers
     */
    public function registerExceptionsHandlers() {
    	// Handle invalid login credentials
		\App::error(function(\UserNotLoggedInException $exception, $code)
		{
			$response =  new \app\Core\Response\Response;
			return $response->respondWithError($exception->getMessage());
		});
    }

}