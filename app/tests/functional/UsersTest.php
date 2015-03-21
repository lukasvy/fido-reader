<?php namespace app\tests\functional;

use app\tests\Creator\UserCreator as UserCreator;
use app\tests\ApiTestCase as ApiTestCase;

class UserTest extends ApiTestCase {

	/** @test **/
	public function get_all_users_within_application (){
		

		$this->times(4)->create(new UserCreator());
		$user = $this->times(1)->create(new UserCreator(['role'=>'admin']));
		
		$this->be($user);

		$result = $this->getJson('/users','GET');
		
		dd($result);
	}

}