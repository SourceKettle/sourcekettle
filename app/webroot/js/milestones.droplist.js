
// Given a column of tasks, equalise the heights of all columns in its row.
// (keeps the kanban view/planning view nice and tidy when we move things around)
function equaliseColumns(column) {
    var maxHeight = 0;

    // This is a bit of a faff, build a jQuery object for 'this column and its siblings'
    var row = $(column).siblings().toArray().concat(column);

    // Set all heights to "best fit", then calculate max height and resize all columns in the row
    $(row).height("");
    $(row).each(function (index, droplist) {
        var height = $(droplist).height();
        maxHeight = height > maxHeight ? height : maxHeight;
    }).height(maxHeight + "px");
}


// Initialise the drag and drop task lists on this page
$(function initTaskDroplists() {

    // Labels for the various statuses - TODO should be i18n-ified
    var taskStatusLabels = {
        open: "Open",
        'in progress': "In Progress",
        resolved: "Resolved",
        dropped: "Dropped"
    };

    // Classes to apply to the status label
    var taskStatusLabelTypes = {
       open: "label-important",
       'in progress': "label-warning",
       resolved: "label-success",
       dropped: "",
       closed: "label-info"
   };

    // Priority labels and icons
   var taskPriorityLabels = {
        blocker: "Blocker",
        urgent:  "Ugent",
        major:   "Major",
        minor:   "Minor"
   };

   var taskPriorityIcons = {
        blocker: "ban-circle",
        urgent:  "exclamation-sign",
        major:   "upload",
        minor:   "download"
   };

    var apiURL = '../../../../api/tasks/update';

    $('.sprintboard-droplist').each(function(index, column){equaliseColumns(column);});

    // Make all columns and the icebox connected sortable lists
	w = $($('.sprintboard-droplist')[1]).width();

    $( ".sprintboard-droplist" ).sortable({
        cursor: "move",

        //cursorAt: {left: 20, top: 20},
		cursorAt: {
			left: Math.floor(w / 2),
		},

        connectWith: ".sprintboard-droplist",

        items: "li.draggable",

        // Allow dropping on empty lists
        dropOnEmpty: true,

        // Nicely animate when returning from invalid drop targets
        revert: 200,

        // Rotate by 2 degrees while being dragged
        start: function(event, ui){

			// Set the lozenge width to the sprintboard column width
			// Avoids dragging a massive lozenge from the icebox
			ui.item.width(w);

			// Rotate, because it looks nifty
            ui.item.css('transform', 'rotate(2deg)');

			// Glowy edges on all valid drop targets
			$('.sprintboard-droplist').addClass('highlight-droptarget');
        },

        // Unrotate when dropped, also stop the click event from
        // happening so we don't click through to the task
        stop: function(event, ui){
            ui.item.css('transform', '');
			$('.sprintboard-droplist').removeClass('highlight-droptarget');
            $( event.toElement ).one('click', function(e){ e.stopImmediatePropagation(); } );
            equaliseColumns(event.target);
            equaliseColumns(ui.item.parent()[0]);
        },

        // When the item is dropped onto a different task list, do an AJAX call to update the status
        receive: function(event, ui){
            var taskLozenge = ui.item;
            var taskID      = parseInt(taskLozenge.attr("data-taskid"), 10);
            var toStatus    = $(this).attr('data-taskstatus');
            var toPrio      = $(this).attr('data-taskpriority');
            var toMilestone = $(this).attr('data-milestone');

            var taskInfo = {
                id : taskID
            };

            if(typeof toPrio != 'undefined'){
                taskInfo.priority = toPrio;
                var prioLabel   = taskLozenge.find(".taskpriority");
            }

            if(typeof toStatus != 'undefined'){
                taskInfo.status = toStatus;
                var statusLabel   = taskLozenge.find(".taskstatus");
            }

            if(typeof toMilestone != 'undefined'){
                taskInfo.milestone_id = toMilestone;
            }

            $.post(apiURL  + '/' + taskID, taskInfo, function (data) {
                if (data.error === "no_error") {
                    
                    // Update task lozenge's status
                    if (toPrio != null) {
                        var icon = '<i class="icon-'+taskPriorityIcons[toPrio]+' icon-white"> </i>';
                        prioLabel.html(taskPriorityLabels[toPrio]+' '+icon);
                    }

                    if (toStatus != null) {
                        statusLabel.html(taskStatusLabels[toStatus]);

                        // Remove any existing label-foo classes, cheers http://stackoverflow.com/questions/2644299/jquery-removeclass-wildcard
                        statusLabel.removeClass(function(index, css){
                            return (css.match (/\blabel-\S+/g) || []).join(' ');
                        });
                        statusLabel.addClass(taskStatusLabelTypes[toStatus]);
                        
                    }

                } else {
                    alert("Problem: "+data.errorDescription);
                    $(ui.sender).sortable('cancel');
                }
            }, "json");

        }

    // Stop text selection while dragging
    }).disableSelection();


});
