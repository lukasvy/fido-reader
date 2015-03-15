<?php namespace app\tests\functional\Traits;

use app\Users\User as User;

trait CreateUserTrait {
	public function createUser($overwrite = [],$isAdmin = false) {
		$user = [
			'email'      => $this->faker->email,
			'password'   => $this->faker->password,
			'active'     => true,
			'username'   => $this->faker->username,
			'role'       => $isAdmin ? 'admin' : 'user',
			'first_name' => $this->faker->firstName,
			'last_name'  => $this->faker->lastName
		];
		$user = array_merge($user,$overwrite);
		while($this->times--) {
			User::create($user);
		}
	}
}