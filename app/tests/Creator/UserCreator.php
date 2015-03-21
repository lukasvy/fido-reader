<?php namespace app\tests\Creator;

class UserCreator extends FakeCreator {

	/**
	 * Which params needs to be unique
	 * @var [array]
	 */
	protected $unique = ['email','username'];

	/**
	 * Class name
	 * @return [string]
	 */
	protected function getClass() {
		return 'Fido\Users\User';
	}
	/**
	 * Returns user parameters
	 * @return array 
	 */
	protected function getParams($faker) {
		return [
			'email'      => $faker->email,
			'password'   => $faker->password,
			'active'     => true,
			'username'   => $faker->username,
			'role'       => 'admin',
			'first_name' => $faker->firstName,
			'last_name'  => $faker->lastName
		];
	}

}