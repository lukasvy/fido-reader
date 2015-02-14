<?php

class Article extends Eloquent {

	protected $table = 'articles';
	public $timestamps = true;
	protected $softDelete = false;

	// return articles based on supplied tags
	public static function all_from_tags($tags = NULL, $page = NULL, $offset = NULL) {
		if (!$tags) {
			return false;
		}
		if (!is_array($tags)) {
			$tags = array($tags);
		}
		$total = 0;
		$tag_ids = array();
		$articles = false;
		foreach ($tags as $tag) {
			$tag_id = Tag::where('tag','ILIKE',$tag)
						->whereActive(true)
						->first();
			if ($tag_id) {
				array_push($tag_ids, $tag_id->id);
			}
		}
		if ($tag_ids && Arrays::size($tag_ids) > 0) {
			$article_tags_ids = Article_tag::select(array('article_id'))
								->whereIn('tag_id', $tag_ids)
								->whereActive(true)
								->get()
								->toArray();
			$article_tags_ids = Arrays::pluck($article_tags_ids,'article_id');
			if ($article_tags_ids && Arrays::size($article_tags_ids) > 0) {
				$articles = Article::whereIn('id',$article_tags_ids)
							->whereActive(true);
				$total = $articles->count();
				$article_tags_ids = implode(',', $article_tags_ids);
				$query = "	
							SELECT a.id,a.url,a.title,a.desc,a.permalink,a.author,f.name as source,a.created_at
							FROM articles a, feeds f
							WHERE a.id in (".$article_tags_ids.")
							AND a.feed_id = f.id
							AND f.active
							ORDER BY a.created_at DESC
						 ";
				if ($offset) {
					if ($page < 0 ) {
						$page = 0;
					}
					if ($offset < 0 ) {
						$offset = 10;
					} 
					$page = $offset * $page;
					$query .= "LIMIT ".$offset." OFFSET ".$page;
				}
				$articles = DB::select($query);
				if ($articles) {
				    return array('articles' => $articles, 'total' => $total);
				}
			}	
		}
		return array('articles' => array(), 'total' => 0);
	}
	
	// returns all tags associated with this aticle
	public static function article_tags ($id = NULL) {
		$result = false;
		if(!$id) {
			return false;
		}
		$article = self::whereId($id)->whereActive(true)->first();
		if ($article) {
			$article_tags = Article_tag::whereArticle_id($id)
							->whereActive(true)
							->get();
			$tags_array = [];
			if ($article_tags) {
				foreach ($article_tags as $tag_id) {
					$tag = Tag::whereId($tag_id->tag_id)
							->whereActive(true)
							->first();
					if ($tag) {
						array_push($tags_array, $tag);	
					}
				}
				$result = $tags_array;
			}
		}
		return $result;
	}
	
	public static function get_popular($max = 20, $key = 0) {

			$search_id = 0;
			$add_query = "";
			if (Auth::check()){
				$user = Auth::user();
				if ($user) {
					$add_query = ", CASE WHEN EXISTS (SELECT id FROM user_articles WHERE user_id = 1 AND article_id = a.id) THEN 1 ELSE 0 END as user_read  ";
				}
			}
			$articles = DB::select("
							SELECT a.id,a.url,a.title,a.desc,a.permalink,a.author,f.name as source,a.created_at,a.footprint $add_query
								FROM articles a, feeds f
								WHERE a.feed_id = f.id
								AND f.active
								ORDER BY a.footprint DESC,a.created_at DESC
								LIMIT ?
						",array($max));
			$search_id = md5(microtime().rand(0,1000));

		if ($articles) {
			$articles = json_decode(json_encode((array) $articles), true);
			$articles = array('articles'=>$articles,'total' => count($articles),'search_id'=>$search_id);
		} else {
			$articles = array();
		}
		return $articles;
	}
}