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
	public function getClass() {
		return 'Fido\Users\User';
	}
	/**
	 * Returns user parameters
	 * @return array 
	 */
	public function getParams() {
		return [
			'email'      => $this->faker->email,
			'password'   => $this->faker->password,
			'active'     => true,
			'username'   => $this->faker->username,
			'role'       => 'admin',
			'first_name' => $this->faker->firstName,
			'last_name'  => $this->faker->lastName
		];
	}

}