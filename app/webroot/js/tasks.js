
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
	// Do not propagate click event through
	return false;
});

// Show a dropdown menu, first making sure all its links do the Right Thing(tm)
function activateLinksAndShow(menu, button) {
	$('a', menu).unbind('click');
	$('a', menu).click(function(event){
		var change = button.attr("data-change");
		var newValue = $(event.currentTarget).attr("data-value");
		var taskLozenge = button.closest('.task-lozenge');
		var taskInfo = {'Task' : {}};
		taskInfo['Task'][change] = newValue;
		updateTask(taskLozenge, taskInfo);
		menu.hide();
		// Prevent the click from taking us to the top of the page
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
	var storyLabel = taskLozenge.find(".task-dropdown-story");

	$.ajax(urlBase  + '/' + taskInfo.id, {
		"data" : taskInfo,
		"dataType" : "json",
		"type" : "post",
		"success" : function (data) {
			
			// Which column is the lozenge sat in? (it may have been just dragged here
			// or it may have been updated via a dropdown)
			var currentColumn = taskLozenge.parent();
			
			if (data.error === "no_error") {
				taskInfo = data;

				// Task type changed
				if (taskInfo.TaskType != null && typeLabel.size() == 1) {

					// Update lozenge to reflect the new type
					typeLabel.html(taskInfo.TaskType.label+ ' <b class="caret"></b>');
					typeLabel.removeClass(function(index, css){
						return (css.match (/\blabel-\S+/g) || []).join(' ');
					});
					typeLabel.addClass('label-' + taskInfo.TaskType.class);
					typeLabel.attr('title', 'Type: ' + taskInfo.TaskType.name);

				}
				// Priority changed, fairly straightforward
				if (taskInfo.TaskPriority != null) {

					// Update lozenge to reflect the new priority
					var icon = '<i class="icon-'+taskInfo.TaskPriority.icon+' icon-white"> </i>';
					if (prioTextLabel.size() > 0) {
						prioLabel.html(icon + ' <span class="textlabel">'+taskInfo.TaskPriority.label+'</span> <b class="caret"></b>');
					} else {
						prioLabel.html(icon + ' <b class="caret"></b>');
					}
					prioLabel.attr('title', 'Priority: ' + taskInfo.TaskPriority.name);

					// If there's a droplist for this priority and the lozenge isn't in it, move it into place
					if (currentColumn.attr('data-milestone') != 0 && currentColumn.attr('data-taskpriority') != taskInfo.TaskPriority.name) {
						toColumn = $('.sprintboard-droplist[data-taskpriority="'+taskInfo.TaskPriority.name+'"]');
						if (toColumn.size() == 1) {
							taskLozenge.appendTo(toColumn);
							equaliseColumns(toColumn);
							currentColumn = toColumn;
						}
					}
				}

				// Status changed, more fiddly due to the CSS class change...
				if (taskInfo.TaskStatus != null) {
					labelText = taskInfo.TaskStatus.label;
					if (!statusLabel.attr('data-fulltext') || statusLabel.attr("data-fulltext") == "0") {
						labelText = labelText.charAt(0);
					}
					statusLabel.html(labelText + ' <b class="caret"></b>');

					// Remove any existing label-foo classes, cheers http://stackoverflow.com/questions/2644299/jquery-removeclass-wildcard
					statusLabel.removeClass(function(index, css){
						return (css.match (/\blabel-\S+/g) || []).join(' ');
					});
					statusLabel.addClass('label-'+taskInfo.TaskStatus.class);
					statusLabel.attr('title', 'Status: ' + taskInfo.TaskStatus.label);
					taskLozenge.attr('data-taskstatus', taskInfo.TaskStatus.name);

					// If there's a droplist for this status and the lozenge isn't in it, move it into place
					if (currentColumn.attr('data-milestone') != 0 && currentColumn.attr('data-taskstatus') != taskInfo.TaskStatus.name) {
						toColumn = $('.sprintboard-droplist[data-taskstatus="'+taskInfo.TaskStatus.name+'"]');
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
				if (typeof taskInfo.Assignee.id !== 'undefined') {
					assigneeBox = $('.task-dropdown-assignee', taskLozenge);
					assigneeLabel = assigneeBox.siblings('.assignee-full-label');
					gravatarImage = $('img', assigneeBox);
					gravatarImage.attr('src', taskInfo.Assignee.gravatar+'&size='+gravatarImage.attr('width'));
					if (taskInfo.Assignee.id > 0) {
						assigneeBox.attr('title', 'Assigned to: '+taskInfo.Assignee.name);
						gravatarImage.attr('alt', 'Assigned to: '+taskInfo.Assignee.name);
					} else {
						assigneeBox.attr('title', 'Not assigned');
						gravatarImage.attr('alt', 'Not assigned');
					}
					if (assigneeLabel.hasClass('assignee-full-label')) {
						assigneeLabel.text(taskInfo.Assignee.name);
					}
				}

				// Milestone changed
				if (typeof taskInfo.Milestone.id !== 'undefined' && milestoneLabel.length > 0) {
					label = milestoneLabel.siblings(".milestone-label");
					label.empty();
					if (taskInfo.Milestone.id > 0) {
						milestoneLink = $(document.createElement('a'));
						milestoneLink.attr('title',  "Milestone: "+taskInfo.Milestone.subject);
						milestoneLink.attr('href', taskInfo.Milestone.uri);
						milestoneLink.text(taskInfo.Milestone.subject);
						label.append("Milestone: ");
						label.append(milestoneLink);
					} else {
						label.append("No milestone");
					}
					milestoneLabel.attr("title", "Milestone: "+taskInfo.Milestone.subject);
				}

				// Story changed
				if (typeof taskInfo.Story.id !== 'undefined' && storyLabel.length > 0) {
					label = storyLabel.siblings(".story-label");
					label.empty();
					if (taskInfo.Story.id > 0) {
						storyLink = $(document.createElement('a'));
						storyLink.attr('title',  "Story: "+taskInfo.Story.subject);
						storyLink.attr('href', taskInfo.Story.uri);
						storyLink.text(taskInfo.Story.subject);
						label.append("Story: ");
						label.append(storyLink);
					} else {
						label.append("No story");
					}
					storyLabel.attr("title", "Story: "+taskInfo.Story.subject);
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
			var toMilestone   = $(this).attr('data-milestone');
			var toStory       = $(this).attr('data-story');

			var taskInfo = {'Task' : {}};

			if(typeof toPrio != 'undefined'){
				taskInfo.Task.priority = toPrio;
			}

			if(typeof toStatus != 'undefined'){
				taskInfo.Task.status = toStatus;
			}

			if(typeof toMilestone != 'undefined'){
				taskInfo.Task.milestone_id = toMilestone;
			}

			if(typeof toStory != 'undefined'){
				taskInfo.Task.story_id = toStory;
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
	if (points < 0) {points = 0;}
	var taskInfo = { 'Task' : {
		id : taskId,
		story_points : points
	}};
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

// Links the task dependency lists such that on form submit it picks up the new valules
function linkDependencyLists(form, subtasksList, parentsList) {
        form.submit(function(){
                subtasksList.sortable('toArray').forEach(function(taskId){
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'data[DependsOn][]';
                        hidden.value = taskId;
                        $('form').append(hidden);
                });

                parentsList.sortable('toArray').forEach(function(taskId){
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'data[DependedOnBy][]';
                        hidden.value = taskId;
                        $('form').append(hidden);
                });
        });
}

// Adds callbacks to the dependency lists to change task dependencies via AJAX
function ajaxDependencyLists(projectId, taskPublicId, subtasksList, othersList, parentsList) {
	callback = function(event, ui) {
		newSubtaskList = subtasksList.sortable('toArray');
		newParentList = parentsList.sortable('toArray');
		newTaskData = {Task: {}, DependsOn: [], DependedOnBy: []};
		newTaskData['Task']['public_id'] = taskPublicId;
		newTaskData['Task']['project_id'] = projectId;
		newTaskData['DependsOn'] = newSubtaskList;
		newTaskData['DependedOnBy'] = newParentList;
		apiUrl = ui.sender.parents('[data-api-url]').attr('data-api-url');

		$.ajax(apiUrl +'/' + taskPublicId, {
			"data" : newTaskData,
			"dataType" : "json",
			"type" : "post",
			"error" : function(data) {
				$(ui.sender).sortable('cancel');
				alert("Failed to update dependencies");
			}
		});
	};
	subtasksList.sortable({receive: callback}).disableSelection();
	othersList.sortable({receive: callback}).disableSelection();
	parentsList.sortable({receive: callback}).disableSelection();
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
