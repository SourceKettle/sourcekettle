var open = {};
var closed = {};

// JavaScript code to plot a burndown chart based on log data only
// Falls back to just showing a list of logged changes, which is less fun
$('.burndown-outer').each(function(index, outer) {

	var chartbox = $(outer).find('.burndown-chart');
	var controls = $(outer).find('.burndown-controls');

	// Milestone end date and index for drawing markings
	milestoneStart = $(chartbox).find('.start-date strong').text();
	milestoneStartIndex = -1;
	milestoneEnd = $(chartbox).find('.due-date strong').text();
	milestoneEndIndex = -1;

	// Hide the table of data
	$(chartbox).find('table').hide();

	// Pull the values to plot out of the list
	open   = {
		'tasks'  : {'label' : 'Pending tasks', 'stack' : 'stack',  'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []},
		'points' : {'label' : 'Pending story points', 'stack' : 'stack',  'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []},
		'hours'  : {'label' : 'Pending time (estimated hours)', 'stack' : 'stack',  'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []}
	};
	finished   = {
		'tasks'  : {'label' : 'Finished tasks', 'stack' : 'stack', 'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []},
		'points' : {'label' : 'Finished story points', 'stack' : 'stack', 'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []},
		'hours'  : {'label' : 'Finished time (estimated hours)', 'stack' : 'stack', 'lines' : {'show' : true, 'fill' : true}, 'points' : {'show' : true}, 'data' : []}
	};
	starting = {
		"tasks"  : $(chartbox).find('.start-tasks strong').text(),
		"points" : $(chartbox).find('.start-points strong').text(),
		"hours"  : $(chartbox).find('.start-time strong').text() / 60
	};

	i = 0;
	$(chartbox).find('table tbody tr').each(function(index, row) {
		timestamp = $(row).find('td:eq(0)').text();
		open['tasks']['data'].push([timestamp, $(row).find('td:eq(1)').text()]);
		open['points']['data'].push([timestamp, $(row).find('td:eq(3)').text()]);
		open['hours']['data'].push([timestamp, $(row).find('td:eq(5)').text()/60]);
		finished['tasks']['data'].push([timestamp, $(row).find('td:eq(2)').text()]);
		finished['points']['data'].push([timestamp, $(row).find('td:eq(4)').text()]);
		finished['hours']['data'].push([timestamp, $(row).find('td:eq(6)').text()/60]);
		if (timestamp == milestoneStart) {
			milestoneStartIndex = i;
		}
		if (timestamp == milestoneEnd) {
			milestoneEndIndex = i;
		}
		i++;
	});

	chartEnd = finished.tasks.data[ finished.tasks.data.length-1 ][0];

	ideal = {
		"tasks" :  {"label" : "Ideal", "points" : {"show" : false}, "color" : "#000000", "data" : [
			[milestoneStart, starting.tasks],
			[milestoneEnd, 0]
		]},
		"points" : {"label" : "Ideal", "points" : {"show" : false}, "color" : "#000000", "data" : [
			[milestoneStart, starting.points],
			[milestoneEnd, 0]
		]},
		"hours" :  {"label" : "Ideal", "points" : {"show" : false}, "color" : "#000000", "data" : [
			[milestoneStart, starting.hours],
			[milestoneEnd, 0]
		]},
	};


	// Add tooltip placeholder
	$("<div id='burndown-tooltip'></div>").css({
		position: "absolute",
		display: "none",
		border: "1px solid #fdd",
		padding: "2px",
		"background-color": "#fee",
		opacity: 0.80
	}).appendTo("body");

	plotAccordingToChoices(outer);

	// Handle hovering events and render the tooltip
	$(chartbox).bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);

			$("#burndown-tooltip").html(x + " = " + y)
				.css({top: item.pageY+5, left: item.pageX+5})
				.fadeIn(200);
		} else {
			$("#burndown-tooltip").hide();
		}
	});

	// Redraw whenever the controls are frobbed
	$(controls).find('input').change(function() {
		plotAccordingToChoices(outer);
	});

});

function plotAccordingToChoices(outer) {

	// Get hold of the chart box and controls
	// We expect the chart box to contain a table of data, we'll plot the chart on top of it
	var chartbox = $(outer).find('.burndown-chart');
	var controls = $(outer).find('.burndown-controls');
	
	// Show points, number of tasks or hours?
	var display = $(controls).find('input[name=series]:checked').val();

	// Should we show finished tasks as well as open tasks? Stacked for ease of viewing
	var show_finished = $(controls).find('input[name=show_finished]:checked').val();

	// Pick out the series we want, and optionally add finished tasks stacked on top
	var data = [open[display]];

	if (show_finished) {
		data.push(finished[display]);
	}

	// Add the "ideal" line, showing ideal velocity
	data.push(ideal[display]);

	// X-index of the milestone end date
	chartEndIndex = open[display].data.length-1;

	// Vertical lines marking the start and end of the milestone, if needed
	var markings = [];

	// Blue line at the start...
	if (milestoneStartIndex > 0) {
		markings.push(
			{'color': '#1111ee', 'lineWidth': 2, 'xaxis' : {'from' : milestoneStartIndex, 'to' : milestoneStartIndex}}
		);
	}

	// Red line at the end, and shade the "overdue zone" in light red
	if (milestoneEndIndex > -1 && milestoneEndIndex < data[0].data.length) {
		markings.push(
			{'color': '#ee1111', 'lineWidth': 2, 'xaxis' : {'from' : milestoneEndIndex, 'to' : milestoneEndIndex}}
		);
		markings.push(
			{'color': '#ffcccc', 'xaxis' : {'from' : milestoneEndIndex, 'to' : chartEndIndex}}
		);
	}
	var plot = $.plot(chartbox, data, {
        'xaxis' : {
			'mode' : 'categories'
		},
        'yaxis' : {
			'min' : 0,
			'tickDecimals' : 0
		},
		'grid' : {
			'markings' : markings,
		}
    });

	// Annotate the start and end lines
	var o;
	if (milestoneStartIndex > 0) {
		o = plot.pointOffset({ x: milestoneStartIndex, y: 0});
		chartbox.append("<div style='position:absolute;left:" + (o.left + 4) + "px;top:" + plot.height()/2 + "px;color:#1111ee;font-size:smaller'>Milestone starts</div>");
	}
	if (milestoneEndIndex > -1 && milestoneEndIndex < data[0].data.length) {
		o = plot.pointOffset({ x: milestoneEndIndex, y: 0});
		chartbox.append("<div style='position:absolute;left:" + (o.left + 4) + "px;top:" + plot.height()/2 + "px;color:#ee1111;font-size:smaller'>Overdue zone</div>");
	}
	
}
