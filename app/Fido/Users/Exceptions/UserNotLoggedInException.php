<?php 

namespace Fido\Users\Exceptions;

class UserNotLoggedInException extends \Exception {
	protected $message = 'Invalid username or password';
	protected $code    = 0;
}