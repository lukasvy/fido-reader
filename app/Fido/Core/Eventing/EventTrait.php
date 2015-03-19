<?php

namespace Fido\Core\Eventing;

trait EventTrait {

	private $events = [];

	public function fire (Event $event) {
	 	\Event::fire($this->getEventProperName($event), array($event));
	 	return $this;
	}

	public function raise (Event $event) {
		$this->events[] = ["name" => $this->getEventProperName($event), "obj" => $event];
		return $this;
	}

	private function getEventProperName ($event) {
		$name = get_class($event);
		$name = str_replace('\\','.',$name);
		return $name;
	}

	public function dispatch() {
		foreach ($this->events as $event) {
			\Event::fire($event["name"], [$event["obj"]]);
		}
		$this->events = [];
		return $this;
	}
}