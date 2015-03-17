<?php namespace app\tests\Creator;

use Faker\Factory as Faker;

abstract class FakeCreator implements FakeCreatorInterface {

	protected $overwrite;
	protected $faker;

	public function __construct($overwrite = []) {
		$this->overwrite = $overwrite;
		$this->faker 	 = Faker::create();
	}

	abstract public function getClass();
	abstract public function getParams();

}