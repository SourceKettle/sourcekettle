$('.comment').find(':button.edit').bind('click', function() {
	$('.comment form').hide();
    var p = $(this).parent('.comment');
    p.find('p').hide();
    p.find('form').show();
});

$('.comment').find (':button.delete').click (function() {
	var result = confirm ('Are you sure you want to delete this comment?');
	if ( result ) {
		$(this).parent('.comment').find('form.comment-delete').submit();
	}
});
