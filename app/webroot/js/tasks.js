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
	var button = $(event.currentTarget);
	var menu = button.attr('data-toggle');
	menu = $('#'+menu);

	// Menu is open: hide it (and any other menus...)
	if (menu.is(":visible")) {
		$('.task-dropdown-menu').hide();

	// Menu is closed: close all menus, move the desired one into place, and show it
	} else {
		$('.task-dropdown-menu').hide();
		menu.css('top', button.offset().top + button.outerHeight());
		menu.css('left', button.offset().left);

		// If it needs populating with data, do so...
		source = button.attr('data-source');
		if (source) {
			// TODO could reduce number of queries by not reloading this every time...
			menu.empty();
			$.ajax(source, {
				"dataType" : "json",
                "type" : "get",
				"success" : function (data) {
					menu.append('<li class="label"><a class="" href="#" data-collab-id="0">(Remove assignee)</a></li>');
					for (var id in data) {
						collaborator = data[id];
						menu.append('<li class="label"><a class="" href="#" data-collab-id="'+id+'">'+collaborator+'</a></li>');
					}
					activateLinksAndShow(menu);
				},
				"error" : function () {
					alert("Failed to load collaborator list!");
				}
			});

		// Otherwise, just show it immediately
		} else {
			activateLinksAndShow(menu);
		}
	}
});

// Show a dropdown menu, first making sure all its links do the Right Thing(tm)
function activateLinksAndShow(menu) {
	//$('a', menu).;
	menu.show();
}
