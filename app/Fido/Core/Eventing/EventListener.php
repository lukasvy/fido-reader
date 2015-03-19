<?php

namespace Fido\Core\Eventing;

use ReflectionClass;

class EventListener {

	public function handle(Event $event) {
		if ($this->listenerIsRegistered($this->getEventName($event))) {
			call_user_func([$this,"when".$this->getEventName($event)],$event);
		}
	}

	private function getEventName ($event) {
		return (new ReflectionClass($event))->getShortName();
	}

	private function listenerIsRegistered($eventName) {
		$method    = 'when'.$eventName;
		if (method_exists($this,$method)) {
			return true;
		} else {
			return false;
		}
	}
}