<?php

namespace app\Fido\Users\Events;

use Fido\Core\Eventing\Event as Event;

class UserWasLoggedOut extends Event{

	public $event;

	public function __construct($event) {
		$this->event = $event;
	}

}