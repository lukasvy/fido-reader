<?php
/**
 * Class that deals with all feeds
 */
class FeedRepo {

	private $userModel;
	private $feedModel;
	private $userFeedModel;

	public function __construct(User_feed $userFeedModel, User $userModel, Feed $feedModel) {
		$this->userFeed 	= $userFeedModel;
		$this->userModel 	= $userModel;
		$this->feedModel 	= $feedModel;
	}

	/**
	 * Retrieve user feeds
	 * @param  $user  user to take feeds from
	 * @return array  feeds and all unread feeds
	 */
	public function getUserFeeds ($user) {
		$allUnread = 0;
		$feeds     = 0;
		if ($user){
	    	$feeds = 0;	
	    	$feeds = $user->feeds;
	    	if ($feeds) {
	    		$feeds = $feeds->get('id','url','name')->toArray();
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