<?php namespace app\tests\functional;

use app\tests\Creator\UserCreator as UserCreator;
use app\tests\ApiTestCase as ApiTestCase;
use Fido\Users\User as User;

class UserTest extends ApiTestCase {

	/** @test **/
	public function only_logged_in_user_can_access () {
		\Auth::logout();
		$this->setExpectedException('Fido\Users\Exceptions\NotAllowedException');
		$result = $this->getJson('admin/users','GET');
	}

	/** @test **/
	public function check_that_correct_user_can_view_api () {
		\Auth::logout();
		$this->setExpectedException('Fido\Core\Exceptions\NotAllowedException');
		$user = $this->createAndReturn(new UserCreator(['role'=>'user']));
		
		\Auth::loginUsingId($user->id);
		$this->getJson('admin/users','GET');
	}

	/** @test **/
	public function get_all_users_within_application (){
		\Auth::logout();

		$this->times(4)->create(new UserCreator(['role'=>'user']));

		$user = $this->create(new UserCreator(['role'=>'admin']), true);
		
		\Auth::loginUsingId($user->id);

		$result = $this->getJson('admin/users','GET');
		
		dd($result);
	}

}