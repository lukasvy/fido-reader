<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Tick extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:tick';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Tick script that will pull new articles from RSS feeds';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	private function create_tag($tag = NULL) {
		if (!$tag) return false;
		
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$do_not_cache = true;
		// Measure time passed between ticks
		$start_time = microtime(true);
		$articles_count = 0;
		$feed_count = 0;
		$tags_matched = 0;
		$tags_created = 0;
		$created_tag_ids = array();
		$matched_tag_ids = array();
		
 		$feeds = Feed::whereActive(true)->get();
		foreach ($feeds as $feed) {
			$latest_article = Article::whereFeed_id($feed->id)->first();
			if ($latest_article) {
				// finish check for articles with average time
			}
			$stories = Feeder::getFeed($feed->url);
			if ($stories) {
				foreach ($stories as $story) {
					// update feed if necessary
					if ($feed->name == '' || true) {
						$feed_name = false;
						$feed_name = html_entity_decode(substr(strip_tags($story['feed_name']),0,999));
						if ($feed_name) {
							$feed->name = Encoding::toUTF8($feed_name);
							$feed->save();
							// create tags for feeds from name
        	                                        $feed_tags = Encoding::toUTF8(strtolower(preg_replace ('/[^\-a-zA-Z0-9_,; ]/','',$feed_name)));
							$feed_tags = preg_replace ('/-_; /',',',$feed_tags);
							$feed_tags = explode (',',$feed_tags);
							foreach ($feed_tags as $t) {
                                                            $tag = Tag::whereTag($t)->first();
                                                            if (!$tag){
                                                                $tag = new Tag();
                                                                $tag->tag = $t;
                                                                $tag->save();
								$tags_created++;
                                                            }
							    $new_feed_tag = Feed_tag::whereTag_id($tag->id)
									    ->first();
							    if (!$new_feed_tag) {
								$new_feed_tag = new Feed_tag();
								$new_feed_tag->feed_id = $feed->id;
								$new_feed_tag->tag_id = $tag->id;
								$new_feed_tag->save();
							    }
							}
							$tag = false;
						}
					}
					// Check if article already exists
					if ($story['link'] || $story['permalink']) {
						if ($story['link'] !== $story['permalink']) {
							if ($story['link']) {
								$link = $story['link'];
							}
							if ($story['permalink']) {
								$link = $story['permalink'];
							} 
						} else {
							if ($story['permalink']) {
								$link = $story['permalink'];
							}
						}
						if (!Cache::has($link) || $do_not_cache) {
							if (!Article::whereUrl($link)->get()->toArray()) {
								// save only if article that does not exists
								$title = Encoding::fixUTF8(html_entity_decode(strip_tags($story['title'])));
								$author = Encoding::fixUTF8(strip_tags($story['author']));
								$desc = Encoding::fixUTF8(html_entity_decode(strip_tags($story['desc'])));
								$author = Encoding::fixUTF8(html_entity_decode(strip_tags($story['author'])));
								$article_date =  Encoding::fixUTF8($story['date']);
								$category = Encoding::fixUTF8(html_entity_decode(strip_tags($story['category'])));
								$permalink = $story['permalink'];
								//var_dump($story);
								$article = new Article();
								$article->feed_id = $feed->id;
								$article->url = $link;
								$article->permalink = $permalink;
								$article->title = substr($title,0,254);
								$article->desc = substr($desc,0,999);
								$article->category = substr($category,0,499);
								$article->author = substr($author,0,999);
								$article->article_date = substr($article_date,0,999);
								$article->save();
								// get tags
								$category = strtolower(preg_replace ('/[^\-a-zA-Z0-9_,;\s]/','',$category));
								$category = preg_replace ('/-_;\s/',',',$category);
								$title = preg_replace ('/\'s/','',$title);
								$title = strtolower(preg_replace ('/[^\-a-zA-Z0-9_\s]/','',$title));
								$desc_tags = strtolower(preg_replace ('/[^\-a-zA-Z0-9_\s]/','',$desc));
								$tags = explode (',',$category);
								$title_tags = explode(' ',$title);
								$desc_tags = explode(' ',$desc);
								// category tags
								foreach ($tags as $t) {
									$tag = Tag::whereTag($t)->first();
									if (!$tag){
										$tag = new Tag();
										$tag->tag = $t;
										$tag->save();
										array_push($created_tag_ids, $tag->id);
 									}
 									$tag_exists = Article_tag::whereArticle_id($article->id)													->whereTag_id($tag->id)
 													->first();
 									if (!$tag_exists) {
	 									$article_tag = new Article_tag();
										$article_tag->article_id = $article->id;
										$article_tag->tag_id = $tag->id;
										$article_tag->save();
										array_push($matched_tag_ids, $tag->id);
									}
								}
								
								// title tags
								foreach ($title_tags as $t) {
									$tag = Tag::whereTag($t)->first();
									if (!$tag){
										$tag = new Tag();
										$tag->tag = $t;
										$tag->save();
										array_push($created_tag_ids, $tag->id);
 									}
									if ($tag) {
										$tag_exists = Article_tag::whereArticle_id($article->id)													->whereTag_id($tag->id)
 														->first();
 										if (!$tag_exists) {
		 									$article_tag = new Article_tag();
											$article_tag->article_id = $article->id;
											$article_tag->tag_id = $tag->id;
											$article_tag->save();
											array_push($matched_tag_ids, $tag->id);
										}
 									}
								}
								
								// desc tags
								foreach ($desc_tags as $t) {
									$tag = Tag::whereTag($t)->first();
									if ($tag) {
										$tag_exists = Article_tag::whereArticle_id($article->id)													->whereTag_id($tag->id)
 														->first();
 										if (!$tag_exists) {
		 									$article_tag = new Article_tag();
											$article_tag->article_id = $article->id;
											$article_tag->tag_id = $tag->id;
											$article_tag->save();
											array_push($matched_tag_ids, $tag->id);
										}
 									}
								}
								
								$articles_count++;
								if (!$do_not_cache) {
									Cache::put($link,microtime(true),20000);
								}
							}
						}
					}
				}
			}

			$feed_count++;
		}
		
		$total_time = number_format((microtime(true)) - $start_time, 4);
		
		$tick = new TickDb();
		$tick->articles_retrieved = $articles_count;
		$tick->feeds_checked = $feed_count;
		$tick->duration = $total_time;
		$tick->tags_created = count($created_tag_ids);
		$tick->tags_matched = count($matched_tag_ids);
		$tick->save();
	}

}