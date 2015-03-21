<?php

namespace app\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MainServiceProvider extends ServiceProvider {

	/**
	 * Register handlers
	 * @return
	 */
    public function register() {
        $this->bindExceptions();
        $this->bindRepos();
        $this->registerEvents();
        $this->registerExceptionsHandlers();
    }

    public function bindRepos() {
        $this->app->bind('AccessLogRepo','Fido\AccessLog\AccessLogRepo');
    }

    /**
     * Bind exceptions to App container
     */
    public function bindExceptions(){
        $this->app->bind('UserNotLoggedInException','Fido\Users\Exceptions\UserNotLoggedInException');
    }

    /**
     * Global Event handlers
     */
    public function registerEvents () {
        \Event::listen('Fido.*','Fido\Listeners\UserListener');
    }

    /**
     * Global Exception handlers
     */
    public function registerExceptionsHandlers() {
    	// Handle invalid login credentials
		\App::error(function(\UserNotLoggedInException $exception, $code)
		{
			$response =  new Fido\Core\Response\Response;
			return $response->respondWithError($exception->getMessage());
		});

        \App::error(function(ModelNotFoundException $exception, $code){
            $response =  new Fido\Core\Response\Response;
            return $response->respondWithError('Not Found');
        });
    }

}