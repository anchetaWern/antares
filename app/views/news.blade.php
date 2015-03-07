<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
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
		</header>
		<div id="nav">
			<ul>
				<li><a href="/hn">Hacker News</a></li>
				<li><a href="/producthunt">Product Hunt</a></li>
				<li><a href="/github">Github</a></li>
				<li><a href="/medium">Medium</a></li>
				<li><a href="/dn">Designer News</a></li>
				<li><a href="/readability">Readability Top Reads</a></li>
				<li><a href="/slashdot">Slashdot</a></li>
				<li><a href="/php">PHP</a></li>
				<li><a href="/html5">HTML5</a></li>
				<li><a href="/css">CSS</a></li>
				<li><a href="/js">JavaScript</a></li>
				<li><a href="/ruby">Ruby</a></li>
				<li><a href="/db">Database</a></li>
				<li><a href="/programmer">Programmers</a></li>
				<li><a href="/design">Designers</a></li>
				<li><a href="/gamedev">Game Developer</a></li>
				<li><a href="/webdev">Web Development</a></li>
				<li><a href="/web-operations">Web Operations</a></li>
				<li><a href="/web-performance">Web Performance</a></li>
				<li><a href="/tools">Tools</a></li>
				<li><a href="/python">Python</a></li>
				<li><a href="/go">Go</a></li>
				<li><a href="/ios">IOS</a></li>
				<li><a href="/android">Android</a></li>
				<li><a href="/perl">Perl</a></li>
				<li><a href="/devops">DevOps</a></li>
				<li><a href="/wordpress">Wordpress</a></li>
				<li><a href="/nondev">Non-developer</a></li>
			</ul>
		</div>

		<div id="news">
			<span class="small">Last updated: {{ $last_updated }} PHT</span>
			<h2></h2>
			<ul id="items">				
			@foreach($news as $item)
				<li class="item">
					<a href="{{ $item->url }}" target="_blank">{{ $item->title }}</a>
					<div class="actions">
						<a href="http://www.facebook.com/sharer.php?u={{ $item->url }}" target="_blank"><i class="fa fa-facebook"></i></a>
						<a href="https://twitter.com/share?url={{ $item->url }}&text={{ $item->title }}" target="_blank"><i class="fa fa-twitter"></i></a>
						<a href="https://plus.google.com/share?url={{ $item->url }}" target="_blank"><i class="fa fa-google-plus"></i></a>
						<a href="http://www.linkedin.com/shareArticle?url={{ $item->url }}&title={{ $item->title }}" target="_blank"><i class="fa fa-linkedin"></i></a>
					</div>
				</li>
			@endforeach
			{{ $news->links() }}
			</ul>
		</div>
	</div>
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.infinitescroll.js') }}"></script>
	<script src="{{ asset('assets/js/news.js') }}"></script>
</body>
</html>