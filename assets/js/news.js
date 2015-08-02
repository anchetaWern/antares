(function(){

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