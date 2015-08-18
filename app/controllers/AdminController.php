<?php
class AdminController extends BaseController {

	protected $layout = 'layouts.admin';

	public function index($category = null){

		if(is_null($category)){
			$category = 'hn';
		}

		$filter = Input::get('filter');

		
		if($filter){

			try{
				$date = Carbon::parse($filter)->toDateString();
			}catch(Exception $e){
				return Redirect::to($category)
					->with('message', array('type' => 'error', 'text' => 'You have entered an invalid filter'));
			}
			$news = News::where('category', '=', $category)
				->where('status', '=', 1)
				->whereRaw(DB::raw("DATE(timestamp) = '$date'"))
				->orderBy('timestamp', 'DESC')
				->first();

		}else{
			$news = News::where('category', '=', $category)
				->where('status', '=', 1)
				->orderBy('timestamp', 'DESC')
				->first();
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
			$last_updated = Carbon::createFromFormat('Y-m-d H:i:s', $news->timestamp, $server_timezone)
				->toDateString();
		}


		$news = News::where('category', '=', $category)
						->where('status', '=', 1)
						->whereRaw(DB::raw("DATE(timestamp) = '$last_updated'"))
						->orderBy('timestamp', 'DESC')
						->get();


		$sources = file_get_contents(public_path() . '/files/sources.json');
		$news_sources = json_decode($sources, true);
		
		$page_data = array(
			'category' => $category,
			'news_sources' => $news_sources,
			'news' => $news,
			'last_updated' => $last_updated,
			'filter' => $filter,
		);
	
		$this->layout->content = View::make('admin.news', $page_data);

	}


	public function disableNewsItem(){

		$id = Input::get('id');
		DB::table('news')->where('id', '=', $id)->update(array('status' => 0));
		return 'ok';
	}


	public function makeStatic(){
		
		copy("http://localhost:1111/medium", public_path() . "/medium.html");
		return 'ok';
		
	}
}