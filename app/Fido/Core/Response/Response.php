<?php 

namespace Fido\Core\Response;


/**
 * Class that handles response for API
 */
class Response {
	
	// Hold response object
	private $response;
	// Laravel response object
	private $larRes;

	/**
	 * Constructor for response object
	 * @param array $array 
	 */
	public function __construct($array = []) {
		$this->larRes   = \App::make('Response');
		$this->response = $array;
	}

	/**
	 * Respond with json object
	 * @return json 
	 */
	public function respond () {
		return $this->larRes->json($this->response);
	}

	/**
	 * Return error response
	 * @param  string $message 
	 * @return json          
	 */
	public function respondWithError($message) {
		return $this->larRes->json(['error' => $message ]);
	}
}