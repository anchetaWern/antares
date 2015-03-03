<?php

class HomeController extends BaseController {

	public function index($tag = null){

		if(is_null($tag)){
			$tag = 'hn';
		}

		$news = News::where('tags', '=', $tag)
			->orderBy('timestamp', 'DESC')
			->paginate();
		$page_data = array(
			'news' => $news
		);
		return View::make('news', $page_data);
	}

}
