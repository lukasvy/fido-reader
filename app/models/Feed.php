<?php

class Feed extends Eloquent {

	protected $table = 'feeds';
	public $timestamps = true;

	
	protected $guarded = array('id', 'active', 'created_at', 'upadted_at');
	
	private static $rules = array(
        'url'  => 'Required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
    );
	
	public static function validate($input='') {
		$rules = self::$rules;
		return Validator::make($input, $rules);
	}

	/**
	 * Get the tags for feed
	 * @return BelongsToMany
	 */
	public function tags () {
		return $this->belongsToMany('Tag','feed_tags');
	}

	public function users () {
		return $this->belongsToMany('User','user_feeds','feed_id','user_id');
	}
	
	public static function get_user_feeds($user) {
		if ($user) {
			$feeds = User_feed::whereUser_id($user->id)
					 ->get()
					 ->toArray();
			if ($feeds) {
				$feeds_ids = Arrays::pluck($feeds,'feed_id');
				if ($feeds_ids) {
					$feeds = self::whereActive(true)
							 ->whereIn('id', $feeds_ids)
							 ->select('id','url','name')
							 ->get()
							 ->toArray();
					if ($feeds){
						// find read and unread articles
						$unread = 0;
						
						foreach ($feeds as &$feed) {
							$unread = User::get_no_of_unread($user,$feed['id']);
							$feed['unread'] = $unread;
						}
						unset($feed);
						return $feeds;
					}
				}
			}
			
		}
		return false;
	}
	
	// retrieves all articles from specific feed
	public static function get_feed_articles ($id = NULL, $page = NULL, $offset = NULL, $user = NULL) {
		if (!$id) return false;
		$articles = false;
		$total = 0;
		if ($user) {
			$user = User::whereActive(true)
						->whereId($user)
						->first();
			if (!$user) {
				$user = false;
			}
		}
		$feed = self::whereId($id)
					->whereActive(true)
					->first();
		if ($feed) {
			$articles = Article::whereActive(true)
						->select( 
							array('id','created_at','feed_id','author','url','title','desc') 
						)
						->whereFeed_id($feed->id)
						->orderBy('created_at', 'desc');
			$total = $articles->get()->count();
			if ($offset) {
				if ($page < 0) {
					$page = 0;
				}
				if ($offset < 0){
					$offset = 10;
				}
				$articles = $articles
							->take($offset)
							->skip($offset * $page);
			}

			$articles = $articles->orderBy('created_at');
			$articles = $articles->get()->toArray();
			// add when article was red by user if ever
			if ($user) {
				foreach ($articles as &$article) {
					$article_id = $article['id'];
					$created = false;
					$user_feed = User_feed::whereActive(true)
								->whereFeed_id($article_id)
								->first();
					if ($user_feed) {
						$created = $user_feed->created_at;
					}
					$user_art = User_article::whereActive(true)
								->whereArticle_id($article['id']);
					if ($created) {
						$user_art = $user_art->where('created_at','>=',$created);
					}
					$user_art = $user_art->first();
					
					if ($user_art) {
						$article['user_read'] = $user_art->created_at->toFormattedDateString();
					}
				}
				unset($article);
			}
			if ($articles) {
				return array('articles'=> $articles, 'total' => $total);
			}
		}
		return 0;
	}
	
	public static function get_feeds ($id=NULL,$offset=NULL,$skip=NULL,$sort=NULL,$filter=NULL)
	{
		$total = false;
		if ($id) {
			
			$send_feed = [];
			$send_tags = [];
			$feed = self::whereId($id)
					->whereActive(true)
					->first();
			if ($feed) {
				$send_feed['url'] = $feed->url;
				$send_feed['id']  = $feed->id;
				$tags = Feed_tag::whereFeed_id($feed['id'])
					->whereActive(true)
					->get()
					->toArray();
				foreach($tags as $tag) {
					$tag_name = Tag::find($tag['tag_id'])->tag;
					if ($tag_name) {
						array_push($send_tags,$tag_name);
					}
				}
				$send_feed['no_articles'] = Article::whereActive(true)->whereFeed_id($feed->id)->count();
				if ($send_tags) {
					$send_feed['tags']= implode($send_tags,', ');
				}
				if ($send_feed) {
					return array($send_feed);
				}
				return 0;
			}
		}
		
		$filter_feed_tags = [];
		
		// Search for tags
		if ($filter) {
			$filter_tags = Tag::whereActive(true)
						   ->where('tag','ILIKE','%'.$filter['tag'].'%')
						   ->get()
						   ->toArray();
			$filter_tags = Arrays::pluck($filter_tags,'id');
			$filter_feed_tags = [];
			if ($filter_tags) {
				$filter_feed_tags = Feed_tag::whereActive(true)
								->whereIn('tag_id',$filter_tags)
								->get()
								->toArray();
			} else {
				return array(0,0);
			}
			$filter_feed_tags = Arrays::pluck($filter_feed_tags,'feed_id');
		}
		
		$feeds = self::whereActive(true);
		// Filter tags if necessary
		if ($filter_feed_tags && sizeof($filter_feed_tags) > 0) {
			$feeds = $feeds->whereIn('id',$filter_feed_tags);
			$total = $feeds->count();
		} else {
			if ($filter) {
				return array(0,0);
			}
		}
		
		$feeds = $feeds->take($offset)
					->skip($skip)
					->select(array('id', 'url'));
		if (!$sort) {
			$feeds = $feeds->get();
		} else {
			$key = key($sort);
			$feeds = $feeds
					->orderBy($key, $sort[$key])
					->get();
		}
		$feeds = $feeds->toArray();
		$send_feeds = [];
		foreach ($feeds as $feed){
			$tags = Feed_tag::whereFeed_id($feed['id'])
					->whereActive(true)
					->get()
					->toArray();
			$send_tags = [];
			foreach($tags as $tag) {
				$tag_name = Tag::find($tag['tag_id'])->tag;
				if ($tag_name) {
					array_push($send_tags,$tag_name);
				}
			}
			if ($send_tags) {
				$feed['tags']= implode($send_tags,', ');
			}
			array_push($send_feeds,$feed);
		}
		if (!$total) {
		$total = Feed::whereActive(true)
				->count();
		}
		$result = array($send_feeds,$total);
		return $result;
	}
	
	public static function add_edit_feed ($id=NULL,$inputUrl=NULL,$inputTags=NULL) 
	{
		if ($id) {
			$feed = self::whereId($id)
					->whereActive(true)
					->first();
			if (!$feed) {
				return 0;
			}
		} else {
			$v = self::validate(array(
				'url'	=> $inputUrl)
				);
			$feed = Feed::whereUrl($inputUrl)
						->whereActive(true)
						->first();
			if ($v->passes() && !$feed){
				$feed = new Feed();
				$feed->url = $inputUrl;
				$feed->save();
			}
		}
		
		$all_feed_tags = Arrays::pluck(Feed_tag::whereFeed_id($feed->id)
						->whereActive(true)
						->get()
						->toArray()
						,'tag_id');
		$keep_tags = [];
		if ($inputTags) {
			$arrayOfTags = explode(',',$inputTags);
			$arrayOfTags = array_filter($arrayOfTags);
			$arrayOfTags =	str_replace(' ', '', $arrayOfTags);
			foreach ($arrayOfTags as $part) {
				$tag = Tag::whereTag($part)->first();
				if ($tag) {
					$feedTag = Feed_tag::whereTag_id($tag->id)
							  ->whereFeed_id($feed->id)
							  ->whereActive(true)
							  ->first();
					if(!$feedTag) {
						$feedTag = new Feed_tag();
						$feedTag->feed_id = $feed->id;
						$feedTag->tag_id = $tag->id;
						$feedTag->save();
					}
					array_push($keep_tags,$tag->id);
				} else {
					$v = Tag::validate(array('tag' => $part));
					if ($v->passes()) {
						$tag = new Tag();
						$tag->tag = $part;
						$tag->save();
						array_push($keep_tags,$tag->id);
						$feedTag = new Feed_tag();
						$feedTag->feed_id = $feed->id;
						$feedTag->tag_id = $tag->id;
						$feedTag->save();
					} else {
						return 0;
					}
				}
			}
		}
		foreach ($all_feed_tags as $tag) {
			if (!Arrays::contains($keep_tags,$tag)) 
			{
				$feed_tag = Feed_tag::whereTag_id($tag)
						->whereFeed_id($feed->id)
						->whereActive(true)
						->first();
				$feed_tag->active = false;
				$feed_tag->save();	
			}
		}
		return $feed;
	}
	
	public static function remove($id=NULL)
	{
		if ($id)
		{
			if ($feed = self::whereId($id)
					->whereActive(true)
					->first())
			{
				$feed->active = false;
				$feed->save();
			}
		}
	}

}