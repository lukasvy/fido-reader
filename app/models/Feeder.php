<?php

Class Feeder {

	var $feeder;
	
	private function __construct(){
		$this->feeder = $feeder;
	}
	
	public function getFeeder() {
		if (!$this->feeder){
			$this->feeder = new Feeder();
			return $this->feeder;
		} else {
			return $this->feeder;
		}
	}
	
	public static function getDescription ($url) {
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->enable_cache(true);
		$feed->set_cache_location('/tmp/');
		$feed->init();
		$feed->handle_content_type();
		if ($feed->error) {
			return "unknown";
		}
		return $feed->get_description();
	}
	
	public static function getStory ($url) {
		$text = "";
		if (!$url) {
			return $text;
		}
		$html = file_get_contents($url);
		$readability = new Readability2($html, $url);
		$readability->convertLinksToFootnotes = false;
		$result = $readability->init();
		$text = $readability->getTitle()->textContent;
		$text .= $readability->getContent()->innerHTML;
		preg_match('@^(?:http://)?([^/]+)@i',$url,$matches);
		$baseLink = $matches[1];
		//return $baseLink;
		$text = preg_replace('/src="\//', 'src="http://'.$baseLink.'/', $text);
		return $text;
	}
	
	public static function getFeed ($url, $restriction = NULL) {
		
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->enable_cache(true);
		$feed->set_cache_location(storage_path().'/tmp/');
		$feed->init();
		$feed->handle_content_type();
		if ($feed->error) {
			return false;
		}
		$array = array();
		$i = 1;
		$feed_name = $feed->get_title() ? $feed->get_title() : 'unknown';
		$feed_type = $feed->get_type() ? $feed->get_type() : 'unknown';

		foreach($feed->get_items() as $item) {
			$feedpart = array();
			$feedpart['feed_name'] = $feed_name;
			$feedpart['type_name'] = $feed_type;
			$item->get_title() ?
				$feedpart["title"] = $item->get_title() : 
				$feedpart["title"] = "unknown";
			$item->get_link() ?
				$feedpart["link"] = $item->get_link() : 
				$feedpart["link"] = "unknown";
			$item->get_enclosure()->get_thumbnail() ?
                                $feedpart["thumbnail"] = $item->get_enclosure()->get_thumbnail() :
                                $feedpart["thumbnail"] = "";
			$item->get_enclosure()->get_medium() ?
                                $feedpart["media"] = $item->get_enclosure()->get_medium( ) :
                                $feedpart["media"] = "";
			$item->get_enclosure()->get_keywords() ?
                                $feedpart["keywords"] = $item->get_enclosure()->get_keywords( ) :
                                $feedpart["keywords"] = array();
			$item->get_author() ?
				$item->get_author()->get_name() ? 
				$feedpart["author"] = $item->get_author()->get_name() :
				$feedpart["author"] = "unknown" : 
				$feedpart["author"] = "unknown";
			$item->get_author() ?
				$item->get_author()->get_email() ? 
				$feedpart["author_email"] = $item->get_author()->get_email() :
				$feedpart["author_email"] = "unknown" : 
				$feedpart["author_email"] = "unknown";
			$item->get_date() ?
				$feedpart["date"] = $item->get_date() : 
				$feedpart["date"] = "unknown";
			$item->get_permalink() ?
				$feedpart["permalink"] = $item->get_permalink() : 
				$feedpart["permalink"] = "unknown";
			$item->get_category() ?
				$item->get_category()->get_label() ? 
				$feedpart["category"] = $item->get_category()->get_label() :
				$feedpart["category"] = "unknown" : 
				$feedpart["category"] = "unknown";
			$item->get_description() ?
				$feedpart["desc"] = $item->get_description() : 
				$feedpart["desc"] = "unknown";
			$categories = "";
			if ($item->get_categories()) { 
				foreach ($item->get_categories() as $category) {
					$categories .= ",".$category->get_label();
				}
				$feedpart["category"] = $categories;
			} else { 
				$feedpart["category"] = "unknown";
			}
			//var_dump($item->get_categories());
			//return;
			array_push($array,$feedpart);
			if ($restriction && $i++ == $restriction) {
				break;
			};
		}
		unset($feed);
		return $array;
	}
	
}