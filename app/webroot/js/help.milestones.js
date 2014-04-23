
$(function () {

	// Add tooltips to anything with a title
	$( "[title]" ).tooltip();

	// Cut-down version of the drag-and-drop milestone board with no actual functionality
    $( ".sprintboard-droplist" ).sortable({
        cursor: "move",
        cursorAt: {top: 20, left: 20},
        connectWith: ".sprintboard-droplist",

        items: "li.draggable",

        // Allow dropping on empty lists
        dropOnEmpty: true,

        // Rotate by 2 degrees while being dragged
        start: function(event, ui){
            ui.item.css('transform', 'rotate(2deg)');
			// Set the lozenge width to the sprintboard column width
			// Avoids dragging a massive lozenge from the icebox
			ui.item.width($($('.sprintboard-droplist')[0]).width());

			// Glowy edges on all valid drop targets
			$('.sprintboard-droplist').addClass('highlight-droptarget');
        },

        // Unrotate when dropped, also stop the click event from
        // happening so we don't click through to the task
        stop: function(event, ui){
            ui.item.css('transform', '');
			$('.sprintboard-droplist').removeClass('highlight-droptarget');
            $( event.toElement ).one('click', function(e){ e.stopImmediatePropagation(); } );
        },

		// Cancel any drop events
        receive: function(event, ui){
                $(ui.sender).sortable('cancel');
		}
    // Stop text selection while dragging
    }).disableSelection();


});
