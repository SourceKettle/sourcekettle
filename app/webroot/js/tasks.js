// Labels for the various statuses - TODO should be loaded from database
var taskStatusLabels = {
	open: "Open",
	'in progress': "In Progress",
	resolved: "Resolved",
	closed: "Closed",
	dropped: "Dropped"
};

// Classes to apply to the status label
var taskStatusLabelTypes = {
	open: "label-important",
	'in progress': "label-warning",
	resolved: "label-success",
	closed: "label-info",
	dropped: ""
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
	 major:   "arrow-up",
	 minor:   "arrow-down"
};

var taskTypeLabels = {
	bug: "Bug",
	duplicate: "Duplicate",
	enhancement: "Enhancement",
	invalid: "Invalid",
	question: "Question",
	wontfix: "Won't Fix",
	documentation: "Documentation",
	meeting: "Meeting",
	maintenance: "Maintenance Work",
	testing: "Testing",
};

var taskTypeClasses = {
	bug: "important",
	duplicate: "warning",
	enhancement: "success",
	invalid: "",
	question: "info",
	wontfix: "inverse",
	documentation: "info",
	meeting: "info",
	maintenance: "warning",
	testing: "success",
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

// Task subject edit button
$('.task-view-subject').find(':button.edit').bind('click', function() {
	var subject = $(this).parent();
	subject.find('.task-subject-text').hide();
	subject.find('.edit-form').show();
	$(this).hide();
});

// Task description edit button
$('.task-view-description').find(':button.edit').bind('click', function() {
	var description = $(this).parent();
	description.find('.task-description-text').hide();
	description.find('.edit-form').show();
	$(this).hide();
});
// Task dropdown menus - DIY dropdowns...
// Why? Because lozenges have overflow:hidden which hides the dropdown
// menu part of a "normal" bootstrap dropdown, and removing overflow:hidden
// means the innards spill all over the place on smaller screens :-(
$('.task-dropdown').click(function(event) {
	var button = $(event.currentTarget);
	var dataType = button.attr('data-type');
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
					menu.append('<li class="label"><a class="" href="#" data-value="0">(Remove '+dataType+')</a></li>');
					for (var idx in data) {
						dataItem = data[idx];
						menu.append('<li class="label"><a class="" href="#" data-value="'+dataItem.id+'">'+dataItem.title+'</a></li>');
					}
					activateLinksAndShow(menu, button);
				},
				"error" : function () {
					alert("Failed to load "+dataType+" list!");
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
	$('a', menu).unbind('click');
	$('a', menu).click(function(event){
		var change = button.attr("data-change");
		var newValue = $(event.currentTarget).attr("data-value");
		var taskLozenge = button.closest('.task-lozenge');
		var taskInfo = {};
		taskInfo[change] = newValue;
		updateTask(taskLozenge, taskInfo);
		menu.hide();
		// Prevent the click from taking us to th etop of the page
		return false;
	});
	menu.show();
}

// Given a column of tasks, equalise the heights of all columns in its row.
// (keeps the kanban view/planning view nice and tidy when we move things around)
function equaliseColumns(column) {
	var maxHeight = 0;

	// This is a bit of a faff, build a jQuery object for 'this column and its siblings'
	var row = $(column).siblings('.sprintboard-droplist').toArray().concat(column);

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
	var prioTextLabel = prioLabel.find(".textlabel");
	var typeLabel = taskLozenge.find(".tasktype");
	var statusLabel = taskLozenge.find(".taskstatus");
	var milestoneLabel = taskLozenge.find(".task-dropdown-milestone");

	$.ajax(urlBase  + '/' + taskInfo.id, {
		"data" : taskInfo,
		"dataType" : "json",
		"type" : "post",
		"success" : function (data) {
			
			// Which column is the lozenge sat in? (it may have been just dragged here
			// or it may have been updated via a dropdown)
			var currentColumn = taskLozenge.parent();
			
			if (data.error === "no_error") {
			
				// Task type changed
				if (taskInfo.type != null && typeLabel.size() == 1) {

					// Update lozenge to reflect the new type
					typeLabel.html(taskTypeLabels[taskInfo.type] + ' <b class="caret"></b>');
					typeLabel.removeClass(function(index, css){
						return (css.match (/\blabel-\S+/g) || []).join(' ');
					});
					typeLabel.addClass('label-' + taskTypeClasses[taskInfo.type]);
					typeLabel.attr('title', 'Type: ' + taskInfo.type).tooltip();

				}
				// Priority changed, fairly straightforward
				if (taskInfo.priority != null) {

					// Update lozenge to reflect the new priority
					var icon = '<i class="icon-'+taskPriorityIcons[ taskInfo.priority ]+' icon-white"> </i>';
					if (prioTextLabel.size() > 0) {
						prioLabel.html(icon + ' <span class="textlabel">'+taskPriorityLabels[taskInfo.priority]+'</span> <b class="caret"></b>');
					} else {
						prioLabel.html(icon + ' <b class="caret"></b>');
					}
					prioLabel.attr('title', 'Priority: ' + taskInfo.priority).tooltip();

					// If there's a droplist for this priority and the lozenge isn't in it, move it into place
					if (currentColumn.attr('data-taskpriority') != taskInfo.priority) {
						toColumn = $('.sprintboard-droplist[data-taskpriority="'+taskInfo.priority+'"]');
						if (toColumn.size() == 1) {
							taskLozenge.appendTo(toColumn);
							equaliseColumns(toColumn);
							currentColumn = toColumn;
						}
					}
				}

				// Status changed, more fiddly due to the CSS class change...
				if (taskInfo.status != null) {
					labelText = taskStatusLabels[taskInfo.status];
					if (!statusLabel.attr('data-fulltext')) {
						labelText = labelText.charAt(0);
					}
					statusLabel.html(labelText + ' <b class="caret"></b>');

					// Remove any existing label-foo classes, cheers http://stackoverflow.com/questions/2644299/jquery-removeclass-wildcard
					statusLabel.removeClass(function(index, css){
						return (css.match (/\blabel-\S+/g) || []).join(' ');
					});
					statusLabel.addClass(taskStatusLabelTypes[taskInfo.status]);
					statusLabel.attr('title', 'Status: ' + taskInfo.status).tooltip();
					taskLozenge.attr('data-taskstatus', taskInfo.status);

					// If there's a droplist for this status and the lozenge isn't in it, move it into place
					if (currentColumn.attr('data-taskstatus') != taskInfo.status) {
						toColumn = $('.sprintboard-droplist[data-taskstatus="'+taskInfo.status+'"]');
						if (toColumn.size() == 1) {
							taskLozenge.appendTo(toColumn);
							equaliseColumns(toColumn);
							currentColumn = toColumn;
						}
					}

					// Make sure the task is the correct span width for this column
					newspan = currentColumn.attr('data-taskspan');
					if (typeof newspan !== 'undefined') {
						taskLozenge.removeClass(function(index, css){
							return (css.match (/\bspan\d+/g) || []).join(' ');
				   		});
						taskLozenge.addClass('span'+newspan);
					}
					// It's a status change, so make sure we update the story point totals
					refreshStoryPointTotals();
				}

				// Assignee changed - we need to change the gravatar image
				// Note that it can be set to zero for "unassigned"...
				if (typeof taskInfo.assignee_id !== 'undefined') {
					assigneeBox = $('.task-dropdown-assignee', taskLozenge);
					assigneeLabel = assigneeBox.siblings('.assignee-full-label');
					gravatarImage = $('img', assigneeBox);
					gravatarImage.attr('src', data.assignee_gravatar+'&size='+gravatarImage.attr('width'));
					if (taskInfo.assignee_id == 0) {
						assigneeBox.attr('title', 'Not assigned').tooltip();
					} else {
						assigneeBox.attr('title', 'Assigned to: '+data.assignee_name).tooltip();
					}
					if (assigneeLabel.hasClass('assignee-full-label')) {
						assigneeLabel.text(data.assignee_name);
					}
				}

				// Milestone changed
				if (typeof taskInfo.milestone_id !== 'undefined' && milestoneLabel.length > 0) {
					milestoneLink = $(document.createElement('a'));
					milestoneLink.attr('title',  "Milestone: "+data.milestone_subject).tooltip();
					milestoneLink.attr('href', data.milestone_url);
					milestoneLink.text(data.milestone_subject);
					label = milestoneLabel.siblings(".milestone-label");
					label.empty();
					label.append(milestoneLink);
					milestoneLabel.attr("title", "Milestone: "+data.milestone_subject).tooltip();
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
			var toStatus	= $(this).attr('data-taskstatus');
			//var fromStatus  = $(ui.sender).attr('data-taskstatus');
			var toPrio	  = $(this).attr('data-taskpriority');
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
	taskLozenge = $(button).parents('.task-lozenge').eq(0);
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
			pointsBox.parent().attr('title', points+' story points');
			refreshStoryPointTotals();
		},
		"error" : function(data) {
			alert("Story points update failed");
		}
	});
}

// Updates the total number of story points and the total points completed
// Triggered when task statuses or point counts change.
function refreshStoryPointTotals() {
	
	totalBox = $('#points_total');
	completeBox = $('#points_complete');

	// Skip if we have no points boxes
	if (!totalBox || !completeBox) {
		return;
	}

	total = 0;
	complete = 0;

	$.each($('li.task-lozenge .points'), function(idx, points){
		status = $(points).closest('li.task-lozenge').attr('data-taskstatus');
		points = parseInt($(points).text(), 10);
		if (status == 'closed' || status == 'resolved') {
			complete += points;
		}

		if (status != 'dropped') {
			total += points;
		}
	});

	totalBox.text(total);
	completeBox.text(complete);

}

// Activate the +/- buttons for story points
$(function(){
	$('.btn-storypoints').each(function(index, button){
		var type = $(button).text();
		if (type == '+') {
			$(button).on('click', function(event){
				setStoryPoints(button, 1);
				return false;
			});
		} else if (type == '-') {
			$(button).on('click', function(event){
				setStoryPoints(button, -1);
				return false;
			});
		}

	});
});

// Initialise drag and drop milestone board if we have one
$(initTaskDroplists());
