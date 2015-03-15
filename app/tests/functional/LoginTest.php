<?php namespace app\tests;

use app\Users\User as User;

class LoginTest extends ApiTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function createUser($isAdmin = false) {
		$user = [
			'email'      => $this->faker->email,
			'password'   => $this->faker->password,
			'active'     => true,
			'username'   => $this->faker->username,
			'role'       => $isAdmin ? 'admin' : 'user',
			'first_name' => $this->faker->firstName,
			'last_name'  => $this->faker->lastName
		];
		while($this->times--) {
			User::create($user);
		}
	}

	/** @test */
	public function it_logs_in_user_with_correct_credentials (){
		$this->times(1)->createUser();
		$result = $this->getJson('/login');
	}
}