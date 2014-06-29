function equaliseColumns() {
        var maxHeight = 1;

        // Reset the column heights first.
        $('.sprintboard-column').height ("");

        // Then resize them.
        $('.sprintboard-column').each(function (index, column) {
            var height = $(column).height();
            maxHeight = height > maxHeight ? height : maxHeight;
        }).height(maxHeight + "px");
    }


$(function () {

   var taskStatusLabels = {
            open: "Open",
            in_progress: "In Progress",
            resolved: "Resolved",
			dropped: "Dropped"
   };

   var taskStatusLabelTypes = {
       open: "label-important",
       in_progress: "label-warning",
       resolved: "label-success",
       dropped: "",
       closed: "label-info"
   };

   var transitions = {
       open: "../../tasks/stoptask/",
       in_progress: "../../tasks/starttask/",
       resolved: "../../tasks/resolve/",
       dropped: "../../tasks/freeze/"
   };

    equaliseColumns();

    // Make all columns and the icebox connected sortable lists
    $( ".sprintboard-droplist" ).sortable({
        cursor: "move",
        cursorAt: {top: 20, left: 20},
        connectWith: ".sprintboard-droplist",

        items: "li.draggable",

        // Allow dropping on empty lists
        dropOnEmpty: true,

        // Nicely animate when returning from invalid drop targets
        revert: 200,

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
            equaliseColumns();
        },

        // When the item is dropped onto a different task list, do an AJAX call to update the status
        receive: function(event, ui){

            var taskLozenge = ui.item;
            var taskID      = parseInt(taskLozenge.attr("data-taskid"), 10);
            var fromStatus  = ui.sender.attr('data-taskstatus');
            var toStatus    = $(this).attr('data-taskstatus');
            var statusLabel = taskLozenge.find(".taskstatus");


            // Double check that the transition is one we can do (shouldn't get here!)
            if(!transitions[toStatus]){
                alert("Something weird happened. It probably shouldn't have. Sorry about that.");
                $(ui.sender).sortable('cancel');
                return false;
            }

            // Do the AJAX postback to update task status
            var updateURL = transitions[toStatus] + taskID;

            $.post(updateURL, function (data) {
                if (data.error === "no_error") {
                    // Update task lozenge's status
                    statusLabel.html(taskStatusLabels[toStatus]);

                    // Remove any existing label-foo classes, cheers http://stackoverflow.com/questions/2644299/jquery-removeclass-wildcard
                    statusLabel.removeClass(function(index, css){
                        return (css.match (/\blabel-\S+/g) || []).join(' ');
                    });
                    statusLabel.addClass(taskStatusLabelTypes[toStatus]);
                } else {
                    alert("Problem: "+data.errorDescription);
                    $(ui.sender).sortable('cancel');
                }
            }, "json");

        }

    // Stop text selection while dragging
    }).disableSelection();


});
