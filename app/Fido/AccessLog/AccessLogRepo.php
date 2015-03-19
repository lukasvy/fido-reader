<?php

namespace Fido\AccessLog;

class AccessLogRepo {

	public function logUser ($user) {
		$access_log = new AccessLog();
        $access_log->user_id = $user->id;
        $access_log->type = 1; //login
        $access_log->ip = \Request::getClientIp();
        $access_log->save();
	}

}