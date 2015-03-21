<?php namespace app\tests\functional;

use app\tests\Creator\UserCreator as UserCreator;
use app\tests\ApiTestCase as ApiTestCase;

class LoginTest extends ApiTestCase {

	/** @test **/
	public function it_logs_in_user_with_correct_credentials (){
		$credentials = ['password' => 'test', 'username' => 'user'];
		
		$this->times(1)->create(new UserCreator($credentials));
		
		$result = $this->getJson('/login','POST',$credentials);
		
		$this->assertObjectHasAttributes($result,'username','feeds','email','role');
	}

	/** @test **/
	public function user_logs_in_with_incorrect_credentials() {

		$this->setExpectedException('Fido\Users\Exceptions\UserNotLoggedInException');

		$credentials = ['password' => 'test', 'username' => 'user'];
		
		$this->times(1)->create(new UserCreator);

		$result = $this->getJson('/login','POST',$credentials);

	}
}