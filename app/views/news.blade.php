<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Wern Ancheta">
	<meta name="keywords" content="news,web-development,programming,design">
	<meta name="description" content="Your developer news. All in one place.">

       
	<meta property="og:title" content="{{ Config::get('app.title') }}">
	<meta property="og:type" content="website">
	<meta property="og:image" content="{{ asset('assets/img/icon.png') }}">
	<meta property="og:url" content="http://antaresapp.space">
	<meta property="og:description" content="Your developer news. All in one place.">

	    
	<meta name="twitter:card" content="Your developer news. All in one place.">
	<meta name="twitter:title" content="{{ Config::get('app.title') }}">
	<meta name="twitter:description" content="Your developer news. All in one place.">
	<meta name="twitter:image" content="{{ asset('assets/img/favicons/android-icon-72x72.png') }}">

	<title>{{ Config::get('app.title') }}</title>
	<link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/lib/font-awesome/css/font-awesome.min.css') }}">

	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/img/favicon-96x96.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">


</head>
<body>
	<div id="wrapper">
		<header>
			<h1>{{ Config::get('app.title') }}</h1>
			<span class="sub-text">Your developer news. All in one place.</span>
			<div id="mobile-nav">
				<select name="news_sources" id="news_sources">
					@foreach($news_sources as $url => $news_source)
					<option value="{{ $url }}" {{ FormHelper::isSelected($url, $category) }}>{{ $news_source }}</option>
					@endforeach
				</select>
			</div>
		</header>
		<div id="nav">
			<ul>	
				@foreach($news_sources as $url => $news_source)
				<li><a href="{{ $url }}">{{ $news_source }}</a></li>
				@endforeach
			</ul>
		</div>


		<div id="news">
			<h2></h2>
			@if(Session::has('message'))
			<div id="alert" class="{{ Session::get('message')['type'] }}">
				{{ Session::get('message')['text'] }}
			</div>
			@endif
			<div class="small">Last updated: {{ $last_updated }} PHT</div>
			<div class="clear"></div>
			<div class="filter">
				<form method="GET">				
					<label for="filter">Date Filter</label>
					<input type="text" id="filter" name="filter" placeholder="Eg. 2 days ago">
					<button type="submit">Filter</button>
				</form>
			</div>
			<ul id="items">				
			@foreach($news as $key => $item)
				<li class="item">
					<div class="date {{ ToggleHelper::showWhenCurrentDateIsNotEqualtoPrevious($key, $news) }}">{{ Carbon::createFromFormat('Y-m-d H:i:s', $item->timestamp)->format('M d') }}</div>
					<a href="http://{{ $item->url }}" class="item-link" target="_blank">{{ $item->title }}</a>
					<div>
						<a href="http://{{ $item->source }}" class="source">{{ $item->source }}</a>
					</div>
					<div class="actions">
						<a href="http://www.facebook.com/sharer.php?u={{ $item->url }}" class="hidden" target="_blank"><i class="fa fa-facebook"></i></a>
						<a href="https://twitter.com/share?url={{ $item->url }}&text={{ $item->title }}" class="hidden" target="_blank"><i class="fa fa-twitter"></i></a>
						<a href="https://plus.google.com/share?url={{ $item->url }}" class="hidden" target="_blank"><i class="fa fa-google-plus"></i></a>
						<a href="http://www.linkedin.com/shareArticle?url={{ $item->url }}&title={{ $item->title }}" class="hidden" target="_blank"><i class="fa fa-linkedin"></i></a>
						<a href="https://getpocket.com/save?url={{ $item->url }}" class="hidden" target="_blank">Pocket</a>
						<a href="http://ec2-54-68-251-216.us-west-2.compute.amazonaws.com/post/create?content={{ $item->title }} {{ $item->url }}" class="hidden" target="_blank">Ahead</a>
					</div>
				</li>
			@endforeach
			{{ $news->appends(array('filter' => $filter))->links() }}
			</ul>
		</div>
	</div>
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.infinitescroll.js') }}"></script>
	<script src="{{ asset('assets/js/news.js') }}"></script>
</body>
</html>