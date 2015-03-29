<?php

class HomeController extends BaseController {

	public function index($category = null){

		if(is_null($category)){
			$category = 'hn';
		}

		if($category == 'js'){
			$category = 'javascript';
		}

		$filter = Input::get('filter');

		
		if($filter){

			try{
				$date = Carbon::parse($filter)->toDateString();
			}catch(Exception $e){
				return Redirect::back()
					->with('message', array('type' => 'error', 'text' => 'You have entered an invalid filter'));
			}
			$news = News::where('category', '=', $category)
				->whereRaw(DB::raw("DATE(timestamp) = '$date'"))
				->orderBy('timestamp', 'DESC')
				->paginate();

		}else{
			$news = News::where('category', '=', $category)
				->orderBy('timestamp', 'DESC')
				->paginate();
		}

		$page = Input::get('page');
		$news_count = count($news);
		if($news_count == 0 && empty($page)){
			return Redirect::back()
					->with('message', array('type' => 'error', 'text' => 'Your filter did not return any results'));
		}


		$server_timezone = Config::get('app.timezone');

		$last_updated = Carbon::now()->toDateString();
		if($news_count > 0){
			$last_updated = Carbon::createFromFormat('Y-m-d H:i:s', $news[0]->timestamp, $server_timezone)
				->setTimezone('Asia/Manila')
				->format('M d g:i a');
		}

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
			'category' => '/' . $category,
			'news_sources' => $news_sources,
			'news' => $news,
			'last_updated' => $last_updated,
			'filter' => $filter,
		);
		return View::make('news', $page_data);
	}

}
