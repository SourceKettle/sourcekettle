$(function () {
    'use strict';

    var sprintboardColumns = $(".sprintboard-column"),
        fromColumn = null;

    $(".task-container").bind("dragstart", function (ev) {
        var e = ev.originalEvent;

        // The first parameter has to be "Text" or "URL" to work
        // with IE10.
        e.dataTransfer.setData("Text", this.id);
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.dropEffect = "move";

        fromColumn = $(this).parent().get(0);
    });

    sprintboardColumns.bind("dragover", function (ev) {
        var e = ev.originalEvent;

        if (this !== fromColumn) {
            e.preventDefault();
        }
    });

    sprintboardColumns.bind("drop", function (ev) {
        // Remove any invisible wells
        $(this).find("> .invisiblewell").remove();

        var e = ev.originalEvent,
            task_id = e.dataTransfer.getData("Text"),
            task = $("#" + task_id);

        // Ajax call to server, then callback to move the task div.
        task.detach();
        task.appendTo($(this));

        e.preventDefault();
        fromColumn = null;
    });
});