<?php

/**
*  Repository for AccessLog
*/
class AccessRepo
{
	private $access;
	
	function __construct(AccessLog $access)
	{
		$this->access = $access;
	}

	public function logUserAccess($userId, $type, $ip) {
		if ($userId && $type && $ip) {
			$this->access->user_id = $userId;
			$this->access->type    = $type;
			$this->access->ip      = $ip;
			$this->access->save();
		}
	}
}