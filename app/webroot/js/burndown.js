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

	$(chartbox).find('table tbody tr').each(function(index, row) {
		timestamp = $(row).find('td:eq(0)').text();
		open['tasks']['data'].push([timestamp, $(row).find('td:eq(1)').text()]);
		open['points']['data'].push([timestamp, $(row).find('td:eq(3)').text()]);
		open['hours']['data'].push([timestamp, $(row).find('td:eq(5)').text()/60]);
		finished['tasks']['data'].push([timestamp, $(row).find('td:eq(2)').text()]);
		finished['points']['data'].push([timestamp, $(row).find('td:eq(4)').text()]);
		finished['hours']['data'].push([timestamp, $(row).find('td:eq(6)').text()/60]);
	});

	plotAccordingToChoices(outer);

	$(controls).find('input').change(function() {
		plotAccordingToChoices(outer);
	});


	/*$(chartbox).bind("plotselected", function (event, ranges) {

		$.each(plot.getXAxes(), function(_, axis) {
			var opts = axis.options;
			opts.min = ranges.xaxis.from;
			opts.max = ranges.xaxis.to;
		});
		plot.setupGrid();
		plot.draw();
		plot.clearSelection();
	});*/

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
            }
        },
		'selection' : {
			'mode' : 'x'
		}
    });
}
