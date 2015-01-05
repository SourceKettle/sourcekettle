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

// Task dropdown menus - DIY dropdowns...
// Why? Because lozenges have overflow:hidden which hides the dropdown
// menu part of a "normal" bootstrap dropdown, and removing overflow:hidden
// means the innards spill all over the place on smaller screens :-(
$('.task-dropdown').click(function(event) {
	var button = $(event.target);
	var menu = button.attr('data-toggle');
	menu = $('#'+menu);
	if (menu.is(":visible")) {
		$('.task-dropdown-menu').hide();
	} else {
		menu.css('top', button.offset().top + button.height() + 1);
		menu.css('left', button.offset().left);
		$('.task-dropdown-menu').hide();
		menu.show();
	}
});
