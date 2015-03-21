<?php

namespace Fido\Core\Eventing;

use ReflectionClass;

/**
 * Extend this class to use as wildcard event handler
 */
abstract class EventListener {

	/**
	 * This method is called everytime laravel have this 
	 * class registerd in IOC 
	 * e.g. \Event::listen('Fido.*','Fido\Listeners\UserListener');
	 *
	 * Method resolves name of method to be called based on the Event
	 * class name passed where method name is the same as passed Class name
	 * with 'when' prefix
	 * @param  Event  $event 
	 */
	public function handle(Event $event) {
		if ($this->listenerIsRegistered($this->getEventName($event))) {
			call_user_func([$this,"when".$this->getEventName($event)],$event->event);
		}
	}

	/**
	 * Using reflection class to get name of the Class passed
	 * @param  Event $event 
	 * @return string
	 */
	private function getEventName (Event $event) {
		return (new ReflectionClass($event))->getShortName();
	}

	/**
	 * Checks if listener is registered within extended class
	 * @param  string $eventName 
	 * @return bool
	 */
	private function listenerIsRegistered($eventName) {
		$method    = 'when'.$eventName;
		if (method_exists($this,$method)) {
			return true;
		} else {
			return false;
		}
	}
}