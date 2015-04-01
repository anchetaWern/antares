(function(){

	var loading_options = {
	    finishedMsg: "<div class='end-msg'>Congratulations! You've reached the end of the internet</div>",
	    speed: 'slow',
	    msgText: "<div class='center'>Loading posts...</div>",
	    img: "/assets/img/ajax-loader.gif"
	};

	$('#items').infinitescroll({
	  loading: loading_options,
	  navSelector  : "#news .pagination",
	  nextSelector : "#news .pagination li.active + li a",
	  itemSelector : "#items li.item"
	});

	var path = window.location.pathname;
	if(path == '/'){
		path = '/hn';
	}
	$('a[href="' + path + '"]').addClass('active');

	$('#items').on('click', '.item-link', function(){
		$(this).siblings('.actions').children('a').removeClass('hidden');
	});

	$('#news_sources').change(function(){
		var value = $(this).val();
		window.location.href = value;
	});
})();