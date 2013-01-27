$(function () {
    'use strict';

    var taskContainers = $(".task-container"),
        sprintboardColumns = $(".sprintboard-column"),
        fromColumn = null,
        taskStatusLabels = {
            open: "Open",
            in_progress: "In Progress",
            resolved: "Resolved"
        },
        taskStatusLabelTypes = {
            open: "label-important",
            in_progress: "label-warning",
            resolved: "label-success label-info"
        };

    taskContainers.bind("dragstart", function (ev) {
        $(this).css("opacity", "0.4");

        var e = ev.originalEvent;

        // The first parameter has to be "Text" or "URL" to work
        // with IE10.
        e.dataTransfer.setData("Text", this.id);
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.dropEffect = "move";

        fromColumn = $(this).parent().get(0);
    });

    taskContainers.bind("dragend", function () {
        $(this).css("opacity", "1.0");
    });

    sprintboardColumns.bind("dragover", function (ev) {
        var e = ev.originalEvent,
            from_status = $(fromColumn).attr("data-taskstatus"),
            to_status = $(this).attr("data-taskstatus"),
            task_id = e.dataTransfer.getData("Text"),
            task = $("#" + task_id);

        if (this !== fromColumn && !(from_status === "resolved" && to_status === "in_progress")) {
            e.preventDefault();
        }
    });

    sprintboardColumns.bind("drop", function (ev) {
        var e = ev.originalEvent,
            task_id = e.dataTransfer.getData("Text"),
            task = $("#" + task_id),
            real_id = parseInt(task.attr("data-taskid"), 10),
            column = this,
            transitions = {
                open: {
                    in_progress: "../../tasks/starttask/",
                    resolved: "../../tasks/resolve/",
                    on_ice: ""
                },
                in_progress: {
                    open: "../../tasks/stoptask/",
                    resolved: "../../tasks/resolve/",
                    on_ice: ""
                },
                resolved: {
                    open: "../../tasks/unresolve/",
                    in_progress: "../../tasks/",
                    on_ice: ""
                }
            },
            from_status = $(fromColumn).attr("data-taskstatus"),
            to_status = $(this).attr("data-taskstatus");

        $.post(transitions[from_status][to_status] + real_id,
            function (data) {
                if (data.error === "no_error") {
                    // Remove any invisible wells
                    var $column = $(column),
                        taskStatusLabel = task.find(".taskstatus");

                    $column.find("> .invisiblewell").remove();

                    // Change the task label
                    taskStatusLabel.html(taskStatusLabels[to_status]);
                    taskStatusLabel.removeClass(taskStatusLabelTypes[from_status]);
                    taskStatusLabel.addClass(taskStatusLabelTypes[to_status]);

                } else if (data.error === "failed_to_save") {
                    alert("Could not save.");
                } else if (data.error === "not_assignee") {
                    alert("Cannot start a task you are not assigned to.");
                } else if (data.error === "not_open") {
                    alert("Cannot start this task because it is not open.");
                } else if (data.error === "not_in_progress") {
                    alert("Cannot start this task because it is not in progress.");
                }
            }, "json");

        e.preventDefault();
        e.stopPropagation();

        fromColumn = null;
    });
});