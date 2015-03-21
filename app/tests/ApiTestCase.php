<?php namespace app\tests;

use app\tests\Creator\FakeCreatorInterface as FakeCreatorInterface;

class ApiTestCase extends \TestCase {

	protected $times = 1;

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct();
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
    protected function getJson($uri, $method = 'GET', $params)
    {
        return json_decode($this->call($method, $uri,$params)->getContent());
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

	public function create (FakeCreatorInterface $creator, $getCreated = false) {;
		// perform creation n times
		while ($this->times-- > 0) {
			$result = $creator->create();
		}
		// reset times counter
		$this->times = 1;
		// return last result if required
		if ($getCreated) {
			return $result;
		}
		return $this;
	}
}