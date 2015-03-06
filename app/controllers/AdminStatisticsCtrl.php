<?php


class AdminStatisticsCtrl extends \BaseController {

	private $accessLog;

	public function __construct (AccessLog $log){
		$this->accessLog = $log;
	}
	
	public function getIndex() 
	{	
		$response = array();
		if (Cache::has('tickinfo')) {
            $response = Cache::get('tickinfo');
        } else {
		    $response['tick'] = TickDb::whereActive(true)
				->select('created_at', 'articles_retrieved', 'feeds_checked', 'duration', 'tags_created','tags_matched')
				->take(10)
				->orderBy('created_at','desc')
				->get()
				->toArray();
		    $response['articles'] 			= Article::whereActive(true)->count();
    	    $response['articles_innactive'] = Article::whereActive(false)->count();
    	    $response['tags']               = Tag::count();
    	    $response['tags_innactive']     = Tag::whereActive(false)->count();
    	    $response['feeds']              = Feed::whereActive(true)->count();
    	    $response['feeds_innactive']    = Feed::whereActive(false)->count();
		    Cache::add('tickinfo',$response,10);
		}
		$access_log = $this->accessLog->whereActive(true)->orderBy('created_at','desc')->take(10)->get();
		$log_info 	= array();
		foreach ($access_log as $log) {
		    $user = array();
		    if ($log->user_id) {
				$user = User::whereActive(true)->select('email','username')->whereId($log->user_id)->first()->toArray();
		    }
		    
		    $type = 0;
		    switch ($log->type) {
			case 1:
				$type = 'log in';
			break;
			case 2:
				$type = 'log out';
			break;
		    }
		    array_push($log_info,array('created_at'=>$log->created_at,'type'=>$type,'user'=>$user,'ip'=>$log->ip));
		}
		$response['access'] = $log_info;
		$res = new LvResponse($response);
		return $res->respond();
	}
	
	public function postIndex() 
	{
		return 0;	
	}
	
	public function deleteIndex($id=NULL) 
	{
		return 0;
	}
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}