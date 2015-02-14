<?php


class ArticleCtrl extends \BaseController {
	
	public function getIndex($id=NULL) 
	{	
		$id=intval($id);
		if ($id) {
		    $response = new LvResponse(array('article_id'=> $id));
		    return $response->respond();
		}
		$request = new LvRequest();
		$unread = intval($request->get('unread'));
		// if unread is called
		if ($unread && Auth::check() ) {
			$user = Auth::user();
			
		}
		
		$id = intval($request->get('id'));
		if ($id) {
			$article = Article::whereId($id)->whereActive(true)->first();
			if ($article) {
				$url = $article->url;
				if (!preg_match('!^https?://!i', $url)) $url = 'http://'.$url;
				$content = Cache::get($url);
				if (!$content) {
					try {
						$html = file_get_contents($url);
						$r = new Readability($html, $url);
						$r->init();
						$content = $r->articleContent->innerHTML;

					} catch (Exception $e) {
						Log::info('Exception with readability '.$e."\n url".$url);
					}
					if ($content) {
						Cache::add($url, $content, 200);
					}
				}
				//$content = $r->articleContent->innerHTML;
				if (!$content || $content == '<p>Sorry, Readability was unable to parse this page for content.</p>') {
					$content = $article->desc.'<br> ...';
				}
				if (Auth::check()) {
                    $user = Auth::user();
				    $user_article = User_article::whereActive(true)
						    ->whereArticle_id($article->id)
						    ->whereUser_id($user->id)
						    ->first();
				    if (!$user_article) {
						$user_article = new User_article();
	                                	$user_article->user_id = $user->id;
	                                	$user_article->article_id = $article->id;
	                                	$user_article->save();
	                    // save footprint for feed and article
	                    $feed = Feed::whereId($article->feed_id)->first();
						if ($feed) {
							$feed->footprint = $feed->footprint + 1;
							$feed->save();
						}
						$article->footprint = $article->footprint + 1;
						$article->save();
	                    // record tags for user
	                    $article_tags = Article::article_tags($article->id);
	                    if ($article_tags) {
		                    foreach ($article_tags as $article_tag) {
			                    $user_tag = User_tag::whereActive(true)
			                    			->whereUser_id($user->id)
			                    			->whereTag_id($article_tag->id)
			                    			->first();
			                    if ($user_tag) {
				                    $user_tag->footprint = $user_tag->footprint + 1;
			                    } else {
			                    	$user_tag = new User_tag();
				                    $user_tag->user_id = $user->id;
				                    $user_tag->tag_id = $article_tag->id;
				                    $user_tag->footprint = 1;
			                    }
			                    $user_tag->save();
		                    }
	                    }
				    }
				    
				}
				return $content;
			}
		
		}
		
		return 0;
	}
	
	public function postIndex() 
	{
		$id = false;
		$request = new LvRequest();
		$id = intval($request->get('query'));
		$user = intval($request->get('user'));
		if ($id) {
			$article = Article::whereId($id)->whereActive(true)->first();
			if ($article){
				// user requested and red this article
				$response_array = array(
					'article_id'=> $article->id,
					'author'	=> $article->author,
					'tags'		=> Article::article_tags($article->id),
					'created'	=> $article->created_at,
					'title'		=> $article->title,
					'url'		=> $article->url
					);
				if ($user) {
					$user = User::whereActive(true)
							->whereId($user)
							->first();
					if ($user) {
						$user_article = new User_article();
						$user_article->user_id = $user->id;
						$user_article->article_id = $article->id;
						$user_article->save();
						$response_array['user_id'] = $user->id;
					}
				}
				$response = new LvResponse($response_array);
				return $response->respond();
			}
		}
		return 0;
	}
	
	public function popular () {
		$articles = Article::get_popular();
		if (!$articles) {
			$articles = array();
		}
		$response = new LvResponse($articles);
		return $response->respond();
	}
	
	
	public function missingMethod($parameters)
	{
	    return 0;
	}
	
}