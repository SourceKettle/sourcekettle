function equaliseColumns() {
        var maxHeight = 1;

        $('.sprintboard-column-top').height ("");
        $('.sprintboard-column-top').each(function (index, column) {
            var height = $(column).height();
            maxHeight = height > maxHeight ? height : maxHeight;
        }).height(maxHeight + "px");

        $('.sprintboard-column-bottom').height ("");
        $('.sprintboard-column-bottom').each(function (index, column) {
            var height = $(column).height();
            maxHeight = height > maxHeight ? height : maxHeight;
        }).height(maxHeight + "px");
    }


$(function () {

   var taskPriorityLabels = {
        blocker: "Blocker",
        urgent:  "Ugent",
        major:   "Major",
        minor:   "Minor"
   };

   var taskPriorityLabelIcons = {
        blocker: "ban-circle",
        urgent:  "exclamation-sign",
        major:   "upload",
        minor:   "download"
   };

   var transitions = {
       blocker:  "../../tasks/setBlocker/",
       urgent:   "../../tasks/setUrgent/",
       major:    "../../tasks/setMajor/",
       minor:    "../../tasks/setMinor/",
       detach:   "../../tasks/detachFromMilestone/"
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
			ui.item.width($($('.sprintboard-droplist')[1]).width());

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
            var fromPrio    = ui.sender.attr('data-taskpriority');
            var toPrio      = $(this).attr('data-taskpriority');
            var prioLabel   = taskLozenge.find(".taskpriority");

            // Double check that the transition is one we can do (shouldn't get here!)
            if(!transitions[toPrio]){
                alert("Something weird happened. It probably shouldn't have. Sorry about that.");
                $(ui.sender).sortable('cancel');
                return false;
            }

            // Do the AJAX postback to update task status
            //var updateURL = transitions[toPrio] + '/' + taskID;
            var updateURL = '/api/tasks/update/'+taskID;
            $.post(updateURL, {'priority' : toPrio}, function (data) {
                if (data.error === "no_error") {
                    
                    // Update task lozenge's status
                    if (toPrio != 'detach') {
                        var icon = '<i class="icon-'+taskPriorityLabelIcons[toPrio]+' icon-white"> </i>';
                        prioLabel.html(taskPriorityLabels[toPrio]+' '+icon);
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
