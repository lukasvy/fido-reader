<?php

namespace Fido\Core\Eventing;

/**
 * Use with classes that are fiiring events.
 * Use service provider to register event handler classes
 * which contain method names that reflect events called > 
 * e.g. method WhenUserWasLoggedIn is fired when Event
 * UserWasLoggedIn was fired and laravel have following class
 * registered in service provider : 
 *
 * \Event::listen('Fido.*','Fido\Listeners\UserListener');
 */
trait EventTrait {

	// Store events in array to dispatch later
	private $events = [];

	/**
	 * Fire events at once + pass event object to use with event handler
	 * 
	 * @param Event $event
	 * @return $this
	 */
	public function fire (Event $event) {
	 	\Event::fire($this->getEventProperName($event), array($event));
	 	return $this;
	}

	/**
	 * Store event into event array for later dispatch
	 * @param  Event  $event [description]
	 * @return [type]        [description]
	 */
	public function raise (Event $event) {
		$this->events[] = ["name" => $this->getEventProperName($event), "obj" => $event];
		return $this;
	}

	/**
	 * Get name of the event that reflects the name 
	 * of class that event generated
	 * e.g. Fido\Users\Evets\UserWasLoggedIn 
	 * will become Fido.Users.Events.UserWasloggedIn
	 * 
	 * @param  string $event 
	 * @return string        
	 */
	private function getEventProperName ($event) {
		$name = get_class($event);
		$name = str_replace('\\','.',$name);
		return $name;
	}

	/**
	 * Dispatch all events from array
	 * @return this
	 */
	public function dispatch() {
		foreach ($this->events as $event) {
			\Event::fire($event["name"], [$event["obj"]]);
		}
		$this->events = [];
		return $this;
	}
}