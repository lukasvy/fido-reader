<?php

class FeedRepo {

	private $userModel;
	private $feedModel;
	private $userFeedModel;

	public function __construct(User_feed $userFeedModel, User $userModel, Feed $feedModel) {
		$this->userFeed 	= $userFeedModel;
		$this->userModel 	= $userModel;
		$this->feedModel 	= $feedModel;
	}

	public function getUserFeeds ($user) {
		$allUnread = 0;
		$feeds     = 0;
		if ($user){
	    	$feeds = 0;	
	    	$userFeedIds = $user->feeds->lists('id');
			if ($userFeedIds) {
				$feeds = $this->feedModel
					     ->whereActive(true)
						 ->whereIn('id', $userFeedIds)
						 ->select('id','url','name')
						 ->get()
						 ->toArray();
			}
			if ($feeds){
				// find read and unread articles
				$unread = 0;
				foreach ($feeds as &$feed) {
					$unread = $this->userModel->get_no_of_unread($user,$feed['id']);
					$feed['unread'] = $unread;
				}
				unset($feed);
				return $feeds;
			}
	    	$allUnread = 0;
	    	if ($userFeedIds){
		    	$feeds = $user_feeds;
		    	foreach ($feeds as $feed) {
			    	if ($feed['unread']) {
				    	$allUnread += $feed['unread'];
			    	}
		    	}
	    	}
	    }
	    return array("feeds" => $feeds, "allUnread" => $allUnread);
	}

}