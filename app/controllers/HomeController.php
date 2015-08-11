<?php

class HomeController extends BaseController {

	protected $layout = 'layouts.default';

	public function index($category = null){

		if(is_null($category)){
			$category = 'hn';
		}


		$news = News::where('category', '=', $category)
			->where('status', '=', 1)
			->orderBy('timestamp', 'DESC')
			->first();		


		$page = Input::get('page');
		$news_count = count($news);

		if($news_count == 0 && empty($page)){
			return Redirect::back()
					->with('message', array('type' => 'error', 'text' => 'Your filter did not return any results'));
		}


		$server_timezone = Config::get('app.timezone');

		$last_updated = Carbon::now()->toDateString();
		if($news_count > 0){
			$last_updated = Carbon::createFromFormat('Y-m-d H:i:s', $news->timestamp, $server_timezone)
				->toDateString();
		}


		$news = News::where('category', '=', $category)
								->where('status', '=', 1)
								->whereRaw(DB::raw("DATE(timestamp) = '$last_updated'"))
								->orderBy('timestamp', 'DESC')
								->get();

		$news_sources = array(
			'hn' => array(
				'title' => 'Hacker News',
				'sources' => array(
					'Hacker News' => 'https://news.ycombinator.com/'
				)
			),
			'producthunt' => array(
				'title' => 'Product Hunt',
				'sources' => array(
					'Product Hunt' => 'http://www.producthunt.com/'
				)
			),
			'github' => array(
				'title' => 'Github',
				'sources' => array(
					'Github' => 'https://github.com/trending'
				)
			),
			'medium' => array(
				'title' => 'Medium',
				'sources' => array(
					'Medium' => 'https://medium.com/'
				)
			),
			'dn' => array(
				'title' => 'Designer News',
				'sources' => array(
					'Medium' => 'https://news.layervault.com/'
				)
			),
			'readability' => array(
				'title' => 'Readability Top Reads',
				'sources' => array(
					'Readability' => 'https://readability.com/topreads/'
				)
			),
			'slashdot' => array(
				'title' => 'Slashdot',
				'sources' => array(
					'Slashdot' => 'http://slashdot.org'
				)
			),
			'php' => array(
				'title' => 'PHP',
				'sources' => array(
					'PHP Weekly' => 'http://www.phpweekly.com/'
				)
			),
			'html5' => array(
				'title' => 'HTML5',
				'sources' => array(
					'HTML5 Weekly' => 'http://html5weekly.com/'
				)
			),
			'css' => array(
				'title' => 'CSS',
				'sources' => array(
					'CSS Weekly' => 'http://css-weekly.com/',
					'Responsive Design Weekly' => 'http://responsivedesignweekly.com/'
				)
			),
			'js' => array(
				'title' => 'JavaScript',
				'sources' => array(
					'JavaScript Weekly' => 'http://javascriptweekly.com/',
					'Node Weekly' => 'http://nodeweekly.com/',
					'Ember Weekly' => 'http://emberweekly.com/',
					'EchoJS' => 'http://www.echojs.com/'
				)
			),
			'ruby' => array(
				'title' => 'Ruby',
				'sources' => array(
					'Ruby Weekly' => 'http://rubyweekly.com/'
				)
			),
			'db' => array(
				'title' => 'Database',
				'sources' => array(
					'DB Weekly' => 'http://dbweekly.com/',
					'Postgres Weekly' => 'http://postgresweekly.com/',
					'NoSQL Weekly' => 'http://www.nosqlweekly.com/'
				)
			),
			'programmer' => array(
				'title' => 'Programmer',
				'sources' => array(
					'Status Code' => 'http://statuscode.org/'
				)
			),
			'design' => array(
				'title' => 'Designers',
				'sources' => array(
					'Sidebar' => 'http://sidebar.io/'
				)
			),
			'gamedev' => array(
				'title' => 'Game Development',
				'sources' => array(
					'Gamedev.js Weekly' => 'http://gamedevjsweekly.com/'
				)
			),
			'webdev' => array(
				'title' => 'Web Development',
				'sources' => array(
					'Breaking Development' => 'http://bdconf.com/',
					'Versioning' => 'http://www.sitepoint.com/versioning/',
					'Web Development Reading List' => 'https://wdrl.info/',
					'Web Design Weekly' => 'https://web-design-weekly.com/',
					'Mobile Web Weekly' => 'http://mobilewebweekly.co/',
					'Hey Designer' => 'http://heydesigner.com/'
				)
			),
			'web-operations' => array(
				'title' => 'Web Operations',
				'sources' => array(
					'Web Operations Weekly' => 'http://webopsweekly.com/'
				)
			),
			'web-performance' => array(
				'title' => 'Web Performance',
				'sources' => array(
					'Web Performance News' => 'http://www.webperformancenews.com/'
				)
			),
			'tools' => array(
				'title' => 'Tools',
				'sources' => array(
					'Web Tools Weekly' => 'http://webtoolsweekly.com/'
				)
			),
			'python' => array(
				'title' => 'Python',
				'sources' => array(
					'Python Weekly' => 'http://www.pythonweekly.com/',
					'PyCoders Weekly' => 'http://pycoders.com/'
				)
			),
			'go' => array(
				'title' => 'Go',
				'sources' => array(
					'Go Newsletter' => 'http://golangweekly.com/'
				)
			),
			'ios' => array(
				'title' => 'IOS',
				'sources' => array(
					'IOS Dev Weekly' => 'https://iosdevweekly.com/'
				)
			),
			'android' => array(
				'title' => 'Android',
				'sources' => array(
					'Android Weekly' => 'http://androidweekly.net/'
				)
			),
			'perl' => array(
				'title' => 'Perl',
				'sources' => array(
					'Perl Weekly' => 'http://perlweekly.com/'
				)
			),
			'devops' => array(
				'title' => 'Devops',
				'sources' => array(
					'DevOps Weekly' => 'http://www.devopsweekly.com/'
				)
			),
			'wordpress' => array(
				'title' => 'Wordpress',
				'sources' => array(
					'wpMail' => 'http://wpmail.me/'
				)
			),
			'nondev' => array(
				'title' => 'Non-developer',
				'sources' => array(
					'Next Draft' => 'http://nextdraft.com/'
				)
			)
		);

		$page_data = array(
			'category' => $category,
			'news_sources' => $news_sources,
			'news' => $news,
			'last_updated' => $last_updated
		);
	
		$this->layout->content = View::make('news', $page_data);
	}


}
