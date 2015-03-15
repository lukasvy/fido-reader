<?php namespace app\tests;

class LoginTest extends ApiTestCase {

use Traits\CreateUserTrait;

	public function setUp() {
		parent::setUp();
	}

	/** @test **/
	public function it_logs_in_user_with_correct_credentials (){
		$credentials = ['password' => 'test', 'username' => 'user'];
		$this->times(1)->createUser($credentials,true);

		$result = $this->getJson('/login','POST',$credentials);
		dd($result);
		$this->assertObjectHasAttributes($result,'ok');
	}
}