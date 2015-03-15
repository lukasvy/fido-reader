<?php namespace app\tests;

use Faker\Factory as Faker;

class ApiTestCase extends \TestCase {

	protected $times = 1;
	protected $faker;

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->faker = Faker::create();
	}

	/**
	 * Setup test
	 */
	public function setUp(){ 
		parent::setUp();
		\DB::beginTransaction();
	}

	/**
	 * Destroy test
	 */
	public function tearDown(){ 
		parent::tearDown();
		\DB::rollBack();
	}

	/**
	 * Specifies how many times to run the actoun
	 * @param  Integer $n 
	 * @return self    
	 */
	public function times ($n) {
		$this->times = $n;
		return $this;
	}

	/**
     * Get JSON output from API
     *
     * @param $uri
     * @return mixed
     */
    protected function getJson($uri)
    {
        return json_decode($this->call('GET', $uri)->getContent());
    }

    /**
     * Assert object has any number of attributes
     *
     */
    protected function assertObjectHasAttributes()
    {
        $args = func_get_args();
        $object = array_shift($args);

        foreach ($args as $attribute)
        {
            $this->assertObjectHasAttribute($attribute, $object);
        }
    }


	private function create () {
		while ($this->times-- > 0) {

		}
	}
}