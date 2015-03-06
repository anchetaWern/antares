<?php

class HomeController extends BaseController {

	public function index($tag = null){

		if(is_null($tag)){
			$tag = 'hn';
		}

		if($tag == 'js'){
			$tag = 'javascript';
		}

		$news = News::where('tags', '=', $tag)
			->orderBy('timestamp', 'DESC')
			->paginate();

		$server_timezone = Config::get('app.timezone');

		$last_updated = Carbon::createFromFormat('Y-m-d H:i:s', $news[0]->timestamp, $server_timezone)
			->setTimezone('Asia/Manila')
			->format('M d g:i a');

		$page_data = array(
			'news' => $news,
			'last_updated' => $last_updated
		);
		return View::make('news', $page_data);
	}

}
