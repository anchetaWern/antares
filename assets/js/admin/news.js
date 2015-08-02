var path = window.location.pathname;
if(path == '/'){
	path = '/hn';
}
$('a[href="' + path + '"]').addClass('active');

$('.disble-item').click(function(){
	var self = $(this);
	var id = self.data('id');
	$.post('/admin/disablenewsitem', {'id': id}, function(response){
		self.parents('.item').remove();
		console.log(response);
	});
});