<?php

class Article extends Eloquent {

	protected $table = 'articles';
	public $timestamps = true;
	protected $softDelete = false;

	public static function remove_dead_images ($html, $url) {
	    try {
		//$html = preg_replace('/(href|src)\=\"([^(http)])(\/)?/',"$1=\"".$url."$2",$html);
        	$dom = new DOMDocument();
        	libxml_use_internal_errors(true);
        	$html = htmlentities($html);
        	$dom->loadHTML($html);
        	$images = $dom->getElementsByTagName('img');
        	$i = $images->length - 1;
        	while ($i > -1) {
            	    $node = $images->item($i);
            	    $link = $node->getAttribute('src');
            	    if (!self::checkRemoteFile($link)){
                	$node->parentNode->removeChild($node);
            	    }
            	    $i--;
        	    }
        	    $html = $dom->saveHTML();
        	    $html = html_entity_decode($html);
		} catch (Exception $e) {
		    var_dump($e);
		}
	    return $html;
	}

	public static function retrieve_raw_html ($url) {
            try {
		$html = file_get_contents($url);
        	// check for pics inside hrefs
        	$array = false;
        	if (function_exists('tidy_parse_string')) {
                    $tidy = tidy_parse_string($html, array(), 'UTF8');
                    $tidy->cleanRepair();
                    $html = $tidy->value;
        	}
        	return $html;
	    } catch (Exception $e) {
		//var_dump($e);
		return '';
	    }
        }

	public static function retrieve_article ($url, $article = NULL, $remove_dead_img = true, $cache = true) {
		$content = false;
		if (Cache::has($url.'tick_fetched') && $cache && false) {
		    $content = Cache::get($url.'tick_fetched');
		}
		if (Cache::has($url.'article_fetched') && $cache) {
		    $content = Cache::get($url.'article_fetched');
		}
		if (!$content || $content = '') {
			    try {
				$html = self::retrieve_raw_html($url);
				$r = new Readability2($html, $url);
				$r->init();
				$content = $r->articleContent->innerHTML;
				if ($content) {
				    if ($remove_dead_img) {
					$content = self::remove_dead_images($content, $url);
				    }
	                        }

			   } catch (Exception $e){
				//var_dump($e);
				$content = false;		
			   }		
			}
		if (!$content || 
			$content == '<p>Sorry, Readability was unable to parse this page for content.</p>' ) {
			if ($article) {
				$content = $article->desc.'<br> ...';
			} else {
				$content = '';
			}
		    if ($cache && !Cache::has($url.'article_fetched')) {
                Cache::add($url.'article_fetched', $content, 20);
            }
		}
		return $content;
	} 

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
							SELECT a.id,a.url,a.title,a.desc,a.permalink,a.author,f.name as source,a.created_at,a.media
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
	
	public static function get_popular($max = 20, $key = 0, $page = 0) {

			$search_id = 0;
			if ($key) {
			    $search_id = $key;
			}
			$add_query = "";
			
			if (Auth::check()){
				$user = Auth::user();
				if ($user) {
					$add_query = ", CASE WHEN EXISTS (SELECT id FROM user_articles WHERE user_id = 1 AND article_id = a.id) THEN 1 ELSE 0 END as user_read  ";
				}
			}
			$total = Article::whereActive(true)->count();
			$articles = DB::select("
							SELECT DISTINCT a.id,a.url,a.title,a.desc,a.permalink,a.author,f.name as source,f.url as source_url,a.created_at,a.footprint,a.media $add_query
								FROM articles a, feeds f
								WHERE a.feed_id = f.id
								AND f.active
								ORDER BY a.footprint DESC,a.created_at DESC
								LIMIT ? OFFSET ?
						",array($max,$max*$page));
			$search_id = md5(microtime().rand(0,1000));

		if ($articles) {
			$articles = json_decode(json_encode((array) $articles), true);
			$articles = array('articles'=>$articles,'total' => $total,'search_id'=>$search_id);
		} else {
			$articles = array();
		}
		return $articles;
	}

	public static function checkRemoteFile($url)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    // don't download content
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    if(curl_exec($ch)!==FALSE)
	    {
    		return true;
	    }
	    else
	    {
    		return false;
	    }
	}
}