// Labels for the various statuses - TODO should be loaded from database
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
     urgent:  "Urgent",
     major:   "Major",
     minor:   "Minor"
};

var taskPriorityIcons = {
     blocker: "ban-circle",
     urgent:  "exclamation-sign",
     major:   "upload",
     minor:   "download"
};


// Comment edit buttons
$('.comment').find(':button.edit').bind('click', function() {
	$('.comment form').hide();
    var p = $(this).parent('.comment');
    p.find('p').hide();
    p.find('form').show();
});

// Comment delete buttons
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
					menu.append('<li class="label"><a class="" href="#" data-id="0">(Remove assignee)</a></li>');
					for (var id in data) {
						collaborator = data[id];
						menu.append('<li class="label"><a class="" href="#" data-id="'+id+'">'+collaborator+'</a></li>');
					}
					activateLinksAndShow(menu, button);
				},
				"error" : function () {
					alert("Failed to load collaborator list!");
				}
			});

		// Otherwise, just show it immediately
		} else {
			activateLinksAndShow(menu, button);
		}
	}
});

// Show a dropdown menu, first making sure all its links do the Right Thing(tm)
function activateLinksAndShow(menu, button) {
	$('a', menu).click(function(event){
	
		var changeField = button.attr("data-change")+"_id";
		var newId = $(event.currentTarget).attr("data-id");
		var apiUrl = button.attr("data-api-url");
	});
	menu.show();
}

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


// This function is used to actually update a task via AJAX.
// It is used when dragging/dropping tasks to different statuses/priorities/milestones
// and for the dropdown boxes on each task lozenge.
function updateTask(taskLozenge, taskInfo) {

	taskInfo.id = parseInt(taskLozenge.attr("data-taskid"), 10);
    var urlBase = taskLozenge.attr("data-api-url");
    var prioLabel = taskLozenge.find(".taskpriority");
    var statusLabel = taskLozenge.find(".taskstatus");

    $.ajax(urlBase  + '/' + taskInfo.id, {
		"data" : taskInfo,
		"dataType" : "json",
        "type" : "post",
		"success" : function (data) {
            if (data.error === "no_error") {
            
                if (taskInfo.priority != null) {

					// Update lozenge to reflect the new priority
                    var icon = '<i class="icon-'+taskPriorityIcons[ taskInfo.priority ]+' icon-white"> </i>';
                    prioLabel.html(icon + ' <b class="caret"></b>');

					// If there's a droplist for this priority and the lozenge isn't in it, move it into place
					// TODO
                }

                if (taskInfo.status != null) {
                    statusLabel.html(taskStatusLabels[taskInfo.status].charAt(0) + ' <b class="caret"></b>');

                    // Remove any existing label-foo classes, cheers http://stackoverflow.com/questions/2644299/jquery-removeclass-wildcard
                    statusLabel.removeClass(function(index, css){
                        return (css.match (/\blabel-\S+/g) || []).join(' ');
                    });
                    statusLabel.addClass(taskStatusLabelTypes[taskInfo.status]);
					$(taskLozenge).attr('data-taskstatus', taskInfo.status);

                    // Make sure the task is the correct span width for this column
                    newspan = 'span' + $(taskLozenge).parent().attr('data-taskspan');
                    $(taskLozenge).removeClass(function(index, css){
                        return (css.match (/\bspan\d+/g) || []).join(' ');
                    });
                    $(taskLozenge).addClass(newspan);
                    
					// Number of story points for the task
					taskPoints = parseInt($(taskLozenge).find('.points').text());

					// If we resolved the task, update the "completed story points" count
					if (taskInfo.status == 'resolved') {
						$('#points_complete').text( parseInt($('#points_complete').text()) + taskPoints );

					// If we dropped it, update the total points count
					} else if (taskInfo.status == 'dropped') {
						$('#points_total').text( parseInt($('#points_total').text()) - taskPoints );
					}

					// If we un-resolved the task, update the "completed story points" count
					if (fromStatus == 'resolved') {
						$('#points_complete').text( parseInt($('#points_complete').text()) - taskPoints );

					// If we un-dropped it, update the total points count
					} else if (fromStatus == 'dropped') {
						$('#points_total').text( parseInt($('#points_total').text()) + taskPoints );
					}

					//$('#points-complete').text( $('#points-complete').text() - 1 );
                }

            } else {
                alert("Problem: "+data.errorDescription);
                $(ui.sender).sortable('cancel');
            }
        },
		"error" : function (data) {
            alert("Problem: "+data.statusText);
            $(ui.sender).sortable('cancel');
		}
	});
}

// Initialise the drag and drop task lists on this page
function initTaskDroplists() {

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
            var toStatus    = $(this).attr('data-taskstatus');
            //var fromStatus  = $(ui.sender).attr('data-taskstatus');
            var toPrio      = $(this).attr('data-taskpriority');
            var toMilestone = $(this).attr('data-milestone');

            var taskInfo = {};

            if(typeof toPrio != 'undefined'){
                taskInfo.priority = toPrio;
            }

            if(typeof toStatus != 'undefined'){
                taskInfo.status = toStatus;
            }

            if(typeof toMilestone != 'undefined'){
                taskInfo.milestone_id = toMilestone;
            }

			updateTask(taskLozenge, taskInfo);

        }

    // Stop text selection while dragging
    }).disableSelection();

}

function setStoryPoints(button, difference) {
	taskLozenge = $(button).parents('li').eq(0);
	taskId = parseInt($(taskLozenge).attr('data-taskid'), 10);
	taskStatus = $(taskLozenge).attr('data-taskstatus');
	apiUrl = $(taskLozenge).attr('data-api-url');
	pointsBox = $($(button).siblings('.disabled')[0]).find('.points');
	points = parseInt($(pointsBox).text());
	points += difference;
	if (points <= 0) {return;}
	var taskInfo = {
		id : taskId,
		story_points : points
	};
	$.ajax(apiUrl +'/' + taskId, {
		"data" : taskInfo,
		"dataType" : "json",
		"type" : "post",
		"success" : function (data) {
			pointsBox.text(points);

			if (taskStatus == 'closed' || taskStatus == 'resolved') {
				$('#points_complete').text( parseInt($('#points_complete').text()) + difference );
			}
			if (taskStatus != 'dropped') {
				$('#points_total').text( parseInt($('#points_total').text()) + difference );
			}
		},
		"error" : function(data) {
			alert("Story points update failed");
		}
	});
}

// Activate the +/- buttons for story points
$(function(){
	$('.btn-storypoints').each(function(index, button){
		var type = $(button).text();
		if (type == '+') {
			$(button).on('click', function(event){
				setStoryPoints(button, 1);
			});
		} else if (type == '-') {
			$(button).on('click', function(event){
				setStoryPoints(button, -1);
			});
		}

	});
});

// Initialise drag and drop milestone board if we have one
$(initTaskDroplists());
