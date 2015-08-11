@section('content')
<header>
	<h1>{{ Config::get('app.title') }}</h1>
	<span class="sub-text">Your developer news. All in one place.</span>
	<div id="mobile-nav">
		<select name="news_sources" id="news_sources">
			@foreach($news_sources as $url => $news_source)
			<option value="/{{ $url }}" {{ FormHelper::isSelected($url, $category) }}>{{ $news_source['title'] }}</option>
			@endforeach
		</select>
	</div>
</header>
<div id="nav">
	<ul>	
		@foreach($news_sources as $url => $news_source)
		<li><a href="/{{ $url }}">{{ $news_source['title'] }}</a></li>
		@endforeach
	</ul>
</div>


<div id="news">
	<div id="news-category">
		<h2>{{ $category }}</h2>
		Sources: 
		@foreach($news_sources[$category]['sources'] as $source => $source_url)
			<a href="{{ $source_url }}" target="_blank">{{ $source }}</a>
		@endforeach
	</div>
	@if(Session::has('message'))
	<div id="alert" class="{{ Session::get('message')['type'] }}">
		{{ Session::get('message')['text'] }}
	</div>
	@endif
	<div class="small">Last updated: {{ $last_updated }} PHT</div>
	<div class="clear"></div>
	<ul id="items">				
	@foreach($news as $key => $item)
		<li class="item">
			<div class="date {{ ToggleHelper::showWhenCurrentDateIsNotEqualtoPrevious($key, $news) }}">{{ Carbon::createFromFormat('Y-m-d H:i:s', $item->timestamp)->format('M d') }}</div>
			<a href="{{ $item->url }}" class="item-link" target="_blank">{{ $item->title }}</a>
			<div>
				<a href="http://{{ $item->source }}" class="source" target="_blank">{{ $item->source }}</a>
			</div>
			<div class="actions">
				<a href="http://www.facebook.com/sharer.php?u={{ $item->url }}" class="hidden" target="_blank"><i class="fa fa-facebook"></i></a>
				<a href="https://twitter.com/share?url={{ $item->url }}&text={{ $item->title }}" class="hidden" target="_blank"><i class="fa fa-twitter"></i></a>
				<a href="https://plus.google.com/share?url={{ $item->url }}" class="hidden" target="_blank"><i class="fa fa-google-plus"></i></a>
				<a href="http://www.linkedin.com/shareArticle?url={{ $item->url }}&title={{ $item->title }}" class="hidden" target="_blank"><i class="fa fa-linkedin"></i></a>
				<a href="https://getpocket.com/save?url={{ $item->url }}" class="hidden" target="_blank">Pocket</a>
			</div>
		</li>
	@endforeach
	</ul>
</div>
@stop