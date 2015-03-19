<?php namespace app\tests\Creator;

class UserCreator extends FakeCreator {

	public function getClass() {
		return 'Fido\Users\User';
	}
	/**
	 * Returns user parameters
	 * @return array 
	 */
	public function getParams() {
		$user = [
			'email'      => $this->faker->email,
			'password'   => $this->faker->password,
			'active'     => true,
			'username'   => $this->faker->username,
			'role'       => 'admin',
			'first_name' => $this->faker->firstName,
			'last_name'  => $this->faker->lastName
		];
		$user = array_merge($user,$this->overwrite);
		return $user;
	}

}