<?php

class SearchCtrl extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		/*
$request = new LvRequest();
		$query = $request->get('q');
		$googleAPI = new Googleapi();
		$googAPIResp = $googleAPI->findFeeds($query);
		$response = new LvResponse($googAPIResp);
		return $response->respond();
*/
		$request = new LvRequest();
		$query = $request->get('query');
		$page = $request->get('page');
		if (!$page) {
			$page = 0;
		}
		if ($query) {
			$query = preg_replace('/[^a-zA-Z\-_,;\s]/', '', $query);
			$query = preg_replace('/,;\s]/', '\s', $query);
			$tags = explode(' ',$query);
			if ($tags) {
				$articles = Article::all_from_tags($tags, $page, 10);
				$response = new LvResponse($articles);
				return $response->respond();
			}
		}
	}
	
	public function postIndex() 
	{
		$request = new LvRequest();
		$query = $request->get('query');
		$page = intval($request->get('page'));
		/*
$googleAPI = new Googleapi();
		$googAPIResp = $googleAPI->findFeeds($query);
*/
		if (!$page) {
			$page = 0;
		}
		$query = preg_replace('/[^a-zA-Z\-_,;\s]/', '', $query);
		$query = preg_replace('/,;\s]/', '\s', $query);
		$tags = explode(' ',$query);
		if ($tags) {
			$articles = Article::all_from_tags($tags, $page, 10);
			$articles['offset'] = 10;
			$articles['page'] = $page;
			$response = new LvResponse($articles);
			return $response->respond();
		}
		return 0;
	}
	
	public function postSearch() 
	{
		return 0;
	}

	

}