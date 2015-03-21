<?php

namespace Fido\Core\Exceptions;

class NotAllowedException extends \Exception {

	protected $message = 'Not allowed to access this API';
	protected $code    = 0;
}
