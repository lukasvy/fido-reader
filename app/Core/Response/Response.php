<?php 

namespace app\Core\Response;

/**
 * Class that handles response for API
 */
class Response {
	
	// Hold response object
	private $response;

	/**
	 * Constructor for response object
	 * @param array $array 
	 */
	public function __construct($array = []) {
		$this->response = $array;
	}

	/**
	 * Respond with json object
	 * @return json 
	 */
	public function respond () {
		return \Response::json($this->response);
	}

	/**
	 * Return error response
	 * @param  string $message 
	 * @return json          
	 */
	public function respondWithError($message) {
		return \Response::json(['error' => $message ]);
	}
}