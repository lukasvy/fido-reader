<?php namespace app\tests\functional;

use app\tests\Creator\UserCreator as UserCreator;
use app\tests\AdminTestCase;
use Fido\Users\User as User;

class UserTest extends AdminTestCase {

	/** @test **/
	public function if_checks_that_only_logged_in_user_can_access_api () {
		$this->setExpectedException('Fido\Users\Exceptions\NotAllowedException');
		$result = $this->getJson('admin/users','GET');
	}

	/** @test **/
	public function it_check_that_correct_user_can_view_api () {
		$this->setExpectedException('Fido\Core\Exceptions\NotAllowedException');
		$user = $this->createAndReturn(new UserCreator(['role'=>'user']));
		
		\Auth::loginUsingId($user->id);
		$this->getJson('admin/users','GET');
	}

	/** @test **/
	public function it_fails_when_no_user_is_found (){
		$this->setExpectedException('Illuminate\Database\Eloquent\ModelNotFoundException');
		$this->loginAdmin();
		$result = $this->getJson('admin/users/1','GET');
	}

	/** @test **/
	public function it_gets_user_within_application (){
		$user = $this->times(50)->createAndReturn(new UserCreator());
		// $this->loginAdmin();
		// $result = $this->getJson('admin/users/'.$user->id,'GET');
		$result = User::filter('as')->sort()->paginate();
		$count = $result->count();

		dd($count);
	}

}