<?php

namespace Fido\Users\Events;

use Fido\Core\Eventing\Event as Event;

class UserWasLoggedIn extends Event{

	public $event;

	public function __construct($event) {
		$this->event = $event;
	}

}