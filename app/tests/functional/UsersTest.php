<?php namespace app\tests\functional;

use app\tests\Creator\UserCreator as UserCreator;
use app\tests\ApiTestCase as ApiTestCase;
use Fido\Users\User as User;

class UserTest extends ApiTestCase {

	/** @test **/
	public function get_all_users_within_application (){
		

		$this->times(4)->create(new UserCreator());
		$user = $this->times(1)->create(new UserCreator(['role'=>'admin']), true);
		
		$this->be(User::find($user->id));

		$result = $this->getJson('/users','GET');
		
		dd($result);
	}

}