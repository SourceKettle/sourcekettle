$(function () {

   var taskStatusLabels = {
            open: "Open",
            in_progress: "In Progress",
            resolved: "Resolved"
   };

   var taskStatusLabelTypes = {
       open: "label-important",
       in_progress: "label-warning",
       resolved: "label-success"
   };

   var transitions = {
       open: {
           in_progress: "../../tasks/starttask/",
           resolved: "../../tasks/resolve/",
           on_ice: "../../tasks/freeze/"
       },
       in_progress: {
           open: "../../tasks/stoptask/",
           resolved: "../../tasks/resolve/",
           on_ice: "../../tasks/freeze/"
       },
       resolved: {
           open: "../../tasks/unresolve/",
           in_progress: "../../tasks/starttask/",
           on_ice: "../../tasks/freeze/"
       }
   };

    // Make all columns and the icebox connected sortable lists
    $( ".sprintboard-droplist" ).sortable({
        cursor: "move",
        connectWith: ".sprintboard-droplist",

        items: "li.draggable",

        // Allow dropping on empty lists
        dropOnEmpty: true,

        // Nicely animate when returning from invalid drop targets
        revert: 200,

        // Rotate by 2 degrees while being dragged
        start: function(event, ui){
            ui.item.css('transform', 'rotate(2deg)');
			$('.sprintboard-column,.sprintboard-icebox').addClass('highlight-droptarget');
        },

        // Unrotate when dropped, also stop the click event from
        // happening so we don't click through to the task
        stop: function(event, ui){
            ui.item.css('transform', '');
			$('.sprintboard-column,.sprintboard-icebox').removeClass('highlight-droptarget');
            $( event.toElement ).one('click', function(e){ e.stopImmediatePropagation(); } );
        },

        // When the item is dropped onto a different task list, do an AJAX call to update the status
        receive: function(event, ui){

            var taskLozenge = ui.item;
            var taskID      = parseInt(taskLozenge.attr("data-taskid"), 10);
            var fromStatus  = ui.sender.parent().attr('data-taskstatus');
            var toStatus    = $(this).parent().attr('data-taskstatus');
            var statusLabel = taskLozenge.find(".taskstatus");


            // Double check that the transition is one we can do (shouldn't get here!)
            if(!transitions[fromStatus][toStatus]){
                alert("Something weird happened. It probably shouldn't have. Sorry about that.");
                $(ui.sender).sortable('cancel');
                return false;
            }

            // Do the AJAX postback to update task status
            var updateURL = transitions[fromStatus][toStatus] + taskID;

            $.post(updateURL, function (data) {
                if (data.error === "no_error") {
                    // Update task lozenge's status
                    statusLabel.html(taskStatusLabels[toStatus]);
                    statusLabel.removeClass(taskStatusLabelTypes[fromStatus]);
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
