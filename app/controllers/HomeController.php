<?php

class HomeController extends BaseController {

	public function index($tag){

		$news = News::where('tags', '=', $tag)->paginate();
		$page_data = array(
			'news' => $news
		);
		return View::make('news', $page_data);
	}

}
