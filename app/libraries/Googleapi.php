<?php

class Googleapi {

	private $json = "";
	
	public function __construct() {
		
	}
	
	public function findFeeds($keyword, $restriction = null) {
		if ($keyword) {
			$array = array($keyword);
			$urlEncodedString = http_build_query($array);
			$url = "https://ajax.googleapis.com/ajax/services/feed/find?" .
				"v=1.0&q=$urlEncodedString&num=100&userip=".Request::getClientIp()."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, 'land');
			$body = curl_exec($ch);
			curl_close($ch);
			
			// now, process the JSON string
			$json = json_decode($body);
			// now have some fun with the results...
			
			$result = $this->get_array_from_data($json, $restriction);
			return $result;
		} else {
			return false;
		}
	}
	
	private function get_array_from_data ($data, $restriction = null) {
		$counter = 1;
		$array = array();
		foreach ($data->{'responseData'}->{'entries'} as $object) {
			$part = array();
			//$part['description'] =  strip_tags(Feeder::getDescription($object->{'url'}));
			$part['url'] =  strip_tags($object->{'url'});
			$part['title'] =  strip_tags($object->{'title'});
			$part['contentSnippet'] =  strip_tags($object->{'contentSnippet'});
			$part['link'] =  strip_tags($object->{'link'});
			
			
			array_push($array,$part);
			if ($restriction) {
				if ($counter++ == $restriction) {
					break;
				}
			}
		}
	
		return $array;
	}

}