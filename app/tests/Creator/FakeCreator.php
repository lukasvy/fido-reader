<?php namespace app\tests\Creator;

use Faker\Factory as Faker;

/**
 * Class needs to be instantiated to create fake object
 */
abstract class FakeCreator implements FakeCreatorInterface {

	// Specify which fields needs to be overwritten 
	// with fields from input
	protected $overwrite;

	// Specify which fields should be unique
	protected $unique = [];

	// Faker instance
	protected $faker;

	// used for unique 
	private $index = 1;

	public function __construct($overwrite = []) {
		$this->overwrite = $overwrite;
		$this->faker 	 = Faker::create();
	}

	/**
	 * Use this method to overwrite params with
	 * overwrite array passed to creator + add unique params to them
	 * @return [array]
	 */
	private function getModifiedParams () {
		$model = $this->getParams($this->faker);
		$model = $this->makeUnique($model);

		if ($this->overwrite) {
			$model = array_merge($model,$this->overwrite);
		}
		return $model;
	}

	/** 
	 * Make unique params where necessary
	 * @param [array]
	 */
	private function makeUnique($model) {
		if (sizeof($this->unique) > 0) {
			foreach ($this->unique as $value) {
				foreach ($model as $modelKey => $modelValue) {
					if ($modelKey == $value) {
						$model[$modelKey] = $this->getUnique($modelKey, $modelValue, $this->getClass());
					}
				}
			}
		}
		return $model;
	}

	/**
	 * Retrieves unique value based on already existing values in db
	 * @param  [strin]  $key   
	 * @param  [string] $value 
	 * @param  [string] $class 
	 * @return [string]
	 */
	private function getUnique($key, $value, $class) {
		if ($this->alreadyExists($key,$value,$class)) {
			return $this->getUnique($key, $value.$this->index++, $class);
		} else {
			return $value;
		}
	}

	/**
	 * Check if value already exists in the database
	 * @param  [strin]  $key   
	 * @param  [string] $value 
	 * @param  [string] $class 
	 * @return [bool]
	 */
	private function alreadyExists($key, $value, $class) {
		$object = call_user_func([$class,'where'],[$key,'=',$value]);
		$result = false;
		try {
			if ($object->first()) {
				$result = true;
			}
		} catch (\Exception $e) {

		}
		return $result;
	}

	/**
	 * Creates new model with specified params
	 * @return [Eloquent]
	 */
	public function create () {
		return call_user_func([$this->getClass(),'create'],$this->getModifiedParams());
	}

	/**
	 * Returns full name of class that creator should return
	 * @return [string]
	 */
	abstract protected function getClass();

	/**
	 * Returns array of parameters that class needs to have
	 * plus fakers types that each param should have
	 *
	 * e.g.
	 * $user = [
	 * 	'email'      => $this->faker->email,
	 * 	'password'   => $this->faker->password,
	 * 	'active'     => true,
	 * 	'username'   => $this->faker->username,
	 * 	'role'       => 'admin',
	 * 	'first_name' => $this->faker->firstName,
	 * 	'last_name'  => $this->faker->lastName
	 * ];
	 * return $user;
	 * 
	 * @param [Faker] $faker faker instance will be passed to the function 
	 * automatically
	 * @return [array]
	 */
	abstract protected function getParams($faker);

}