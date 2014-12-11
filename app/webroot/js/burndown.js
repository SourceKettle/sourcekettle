var open = {};
var closed = {};

// JavaScript code to plot a burndown chart based on log data only
// Falls back to just showing a list of logged changes, which is less fun
$('.burndown-outer').each(function(index, outer) {

	var chartbox = $(outer).find('.burndown-chart');
	var controls = $(outer).find('.burndown-controls');

	// Hide the table of data
	$(chartbox).find('table').hide();

	// Pull the values to plot out of the list
	open   = {
		'tasks'  : {'label' : 'Pending tasks', 'data' : []},
		'points' : {'label' : 'Pending story points', 'data' : []},
		'hours'  : {'label' : 'Pending time (estimated hours)', 'data' : []}
	};
	finished   = {
		'tasks'  : {'label' : 'Finished tasks', 'data' : []},
		'points' : {'label' : 'Finished story points', 'data' : []},
		'hours'  : {'label' : 'Finished time (estimated hours)', 'data' : []}
	};
	highs = {
		"tasks" : $(chartbox).find('.high-tasks strong').text(),
		"points" : $(chartbox).find('.high-points strong').text(),
		"hours" : $(chartbox).find('.high-times strong').text()
	};
	
	$(chartbox).find('table tbody tr').each(function(index, row) {
		timestamp = $(row).find('td:eq(0)').text();
		open['tasks']['data'].push([timestamp, $(row).find('td:eq(1)').text()]);
		open['points']['data'].push([timestamp, $(row).find('td:eq(3)').text()]);
		open['hours']['data'].push([timestamp, $(row).find('td:eq(5)').text()/60]);
		finished['tasks']['data'].push([timestamp, $(row).find('td:eq(2)').text()]);
		finished['points']['data'].push([timestamp, $(row).find('td:eq(4)').text()]);
		finished['hours']['data'].push([timestamp, $(row).find('td:eq(6)').text()/60]);
	});

	

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

	var chartbox = $(outer).find('.burndown-chart');
	var controls = $(outer).find('.burndown-controls');
	var display = 'points';
	var show_finished = false;

	display = $(controls).find('input[name=series]:checked').val();
	show_finished = $(controls).find('input[name=show_finished]:checked').val();

	var data = [];
	if (show_finished) {
		data = [open[display], finished[display]];
	} else {
		data = [open[display]];
	}

	var plot = $.plot(chartbox, data, {
        'xaxis' : {
			'mode' : 'categories'
		},
        'yaxis' : {
			'tickDecimals' : 0
		},
        'series' : {
            'stack' : 'stack',
            'lines' : {
                'fill' : true,
                'show' : true,
            },
			'points' : {
				'show': true
			},
			'grid' : {
				'hoverable' : true
			}
        },
    });
	
}
