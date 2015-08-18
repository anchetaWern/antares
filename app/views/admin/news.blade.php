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
		<li><a href="/admin/{{ $url }}">{{ $news_source['title'] }}</a></li>
		@endforeach
	</ul>
</div>


<div id="news">
	<div id="news-category">
		<h2>{{ $category }}</h2>
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
			<input type="checkbox" class="disble-item" data-id="{{ $item->id }}">
			<a href="{{ $item->url }}" class="item-link" target="_blank">{{ $item->title }}</a>
			<div>
				<a href="http://{{ $item->source }}" class="source">{{ $item->source }}</a>
			</div>
		</li>
	@endforeach
	</ul>
</div>
@stop