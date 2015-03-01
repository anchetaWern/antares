<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ Config::get('app.title') }}</title>
	<link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
</head>
<body>
	<div id="wrapper">
		<h1>{{ Config::get('app.title') }}</h1>
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
				<li><a href="/javascript">JavaScript</a></li>
				<li><a href="/ruby">Ruby</a></li>
				<li><a href="/db">Database</a></li>
				<li><a href="/programmer">Programmers</a></li>
				<li><a href="/design">Designers</a></li>
				<li><a href="/gamedev">Game Developer</a></li>
				<li><a href="/webdev">Web Development</a></li>
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
			<ul id="items">				
			@foreach($news as $item)
				<li class="item">
					<a href="{{ $item->url }}" target="_blank">{{ $item->title }}</a>
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