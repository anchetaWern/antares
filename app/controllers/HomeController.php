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

		$news_sources = array(
			'/hn' => 'Hacker News',
			'/producthunt' => 'Product Hunt',
			'/github' => 'Github',
			'/medium' => 'Medium',
			'/dn' => 'Designer News',
			'/readability' => 'Readability Top Reads',
			'/slashdot' => 'Slashdot',
			'/php' => 'PHP',
			'/html5' => 'HTML5',
			'/css' => 'CSS',
			'/js' => 'JavaScript',
			'/ruby' => 'Ruby',
			'/db' => 'Database',
			'/programmer' => 'Programmer',
			'/design' => 'Designers',
			'/gamedev' => 'Game Development',
			'/webdev' => 'Web Development',
			'/web-operations' => 'Web Operations',
			'/web-performance' => 'Web Performance',
			'/tools' => 'Tools',
			'/python' => 'Python',
			'/go' => 'Go',
			'/ios' => 'IOS',
			'/android' => 'Android',
			'/perl' => 'Perl',
			'/devops' => 'Devops',
			'/wordpress' => 'Wordpress',
			'/nondev' => 'Non-developer'
		);

		$page_data = array(
			'tag' => '/' . $tag,
			'news_sources' => $news_sources,
			'news' => $news,
			'last_updated' => $last_updated
		);
		return View::make('news', $page_data);
	}

}
