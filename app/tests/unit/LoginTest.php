<?php

class LoginTest extends TestCase {

	/**
	 * Prepare mockery object
	 * @param  $class class name of object to mock
	 * @return Mockery
	 */
	public function mock($class)
	{
	  $mock = Mockery::mock($class);
	 
	  $this->app->instance($class, $mock);
	 
	  return $mock;
	}

	/**
	 * Setting up test
	 */
	// public function setUp()
	// {
 //  		parent::setUp();
 // 		$this->app['router']->enableFilters();
 //  		$this->mock = $this->mock('Cribbb\Storage\User\UserRepository');
	// }
	 
	public function tearDown()
	{
	    Mockery::close();
	}

	/*
    * @test
    */
    public function testItWorks()
    {
        $this->assertTrue(true);
    }

    /*
    * @test
    */
    public function testLoginValidUser()
    {
    	

    	$accessMock 			= $this->mock('AccessRepo');
		$LvRequestMock 			= $this->mock('LvRequest');
		$LvResponseMock 		= $this->mock('LvResponse');
		$userMock 				= $this->mock('User');
		$returnedUserMock 		= $this->mock('User');
		//$cacheMock		 		= $this->mock('Cache');

		$user = 'admin';
		$password = 'test';
		$userEmail = 'test@test.com';
		$userRole  = 'admin';
		$userId = 1;
		$userIp = '192.168.1.1';
		$userArray = array('user' => 
    			array('username' => $user,
    				  'email'	 => $userEmail,
    				  'role'  	 => $userRole
    				  
    			),
    			'feeds' => array(),
    			'allUnread' => 0
    		);


		Input::replace($input = ['username' => $user,'password' => $password]);

		$accessMock->shouldReceive('logUserAccess')->with($userId,1,$userIp);

		$LvRequestMock->shouldReceive('get')->with('username')->andReturn($user);
		$LvRequestMock->shouldReceive('get')->with('password')->andReturn($password);
		$LvRequestMock->shouldReceive('getClientIp')->andReturn($userIp);

		$LvResponseMock->shouldReceive('setAuthResponse')->with($returnedUserMock)->andReturn($LvResponseMock);
		$LvResponseMock->shouldReceive('respond')->once();
		//$LvResponseMock->shouldReceive('setResponse')->once();
		
		$returnedUserMock->shouldReceive('find')->with($userId)->andReturn($returnedUserMock);
		$returnedUserMock->shouldReceive('getAttribute')->with('username')->andReturn($user);
		$returnedUserMock->shouldReceive('getAttribute')->with('email')->andReturn($userEmail);
		
		$returnedUserMock->shouldReceive('getAttribute')->with('id')->andReturn($userId);

		$credentials = array('username' => $user, 'password' => $password);

		Auth::shouldReceive('attempt')->with($credentials)->andReturn(true);
    	Auth::shouldReceive('user')->andReturn($userId);

  //   	$userMock->shouldReceive('find')->with(1)->andReturn($userMock);

  		$response = $this->call('POST','login');

  		$this->assertEqual(json_encode($userArray),'1');
    }

}