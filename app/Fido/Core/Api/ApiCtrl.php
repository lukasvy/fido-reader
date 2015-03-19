<?php namespace Fido\Core\Api;

use Fido\Core\Response\Response as Response;

class ApiCtrl extends \BaseController {

	/**
	 * Respond in correct format
	 * @param  array $array
	 * @return json
	 */
	public function respond($array = []) {
		$response = new Response($array);
		return $response->respond();
	}
}