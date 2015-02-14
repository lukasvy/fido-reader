<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GooglePoll extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:googlePoll';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Polling RSS feeds from google with random tags';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$start_time = microtime(true);
		//get tag by random and poll feeds from google
		$tags = DB::select("
								SELECT *
								FROM  (
								    SELECT 1 + floor(random() * 5100000)::integer AS id
								    FROM   generate_series(1, 1100) g
								    GROUP  BY 1                     -- trim duplicates
								    ) r
								JOIN tags USING (id)
								LIMIT  1000;   
						   ");
		if ($tags) {
			$tags = json_decode(json_encode((array) $tags), true);
			$tag = $tags[0]['tag'];
			$tag_id = $tags[0]['id'];
			if ($tag) {
				$gApi = new Googleapi();
				$polled = array();
				$result = array();
				try {
					$result = $gApi->findFeeds($tag);
				} catch (Exception $e) {
					
				}
				if ($result) {
					foreach ($result as $feed) {
						if ($feed['url']) {
							$newFeed = Feed::whereUrl($feed['url'])
									->first();
							if (!$newFeed) {
								$newFeed = new Feed();								
								$newFeed->url = $feed['url'];
								$newFeed->name = html_entity_decode(substr(strip_tags($feed['title']),0,999));
								$newFeed->save();
								array_push($polled, $newFeed->id);
							}
						}
					}
					$total_time = number_format((microtime(true)) - $start_time, 4);
					foreach ($polled as $part) {
						$googlePoll = new GooglePollDb();
						$googlePoll->tag_id = $tag_id;
						$googlePoll->feed_id = $part;
						$googlePoll->duration = $total_time;
						$googlePoll->save();
					}
					
				}
			}
		}
		
	}

	

}