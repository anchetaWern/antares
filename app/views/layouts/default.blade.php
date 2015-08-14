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
	<meta property="og:url" content="http://antaresapp.github.io">
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
	@yield('content')

	<div id="app">
		<a href="https://play.google.com/store/apps/details?id=com.wern.antares" target="_blank">
	  		<img src="{{ asset('assets/img/googleplay.png') }}" alt="get the app on google play">
		</a>
	</div>

	</div>
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.infinitescroll.js') }}"></script>
	<script src="{{ asset('assets/js/news.js') }}"></script>
</body>
</html>
