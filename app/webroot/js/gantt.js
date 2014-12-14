var open = {};
var closed = {};

// JavaScript code to plot a gantt chart based on log data only
// Falls back to just showing a list of logged changes, which is less fun
$('.gantt-outer').each(function(index, outer) {

	var chartbox = $(outer).find('.gantt-chart');
	var controls = $(outer).find('.gantt-controls');

	// Hide the table of data
	$(chartbox).find('table').hide();

	// Row list... we'll use this a few times
	rows = $(chartbox).find('table tbody tr');

	// Get the first start date and last due date as the X axis range
	min_x = new Date($(rows[0]).find('td:eq(2)').text());
	max_x = new Date($(rows[rows.length-1]).find('td:eq(3)').text());

	// Get the highest Y value i.e. number of milestones
	max_y = $(chartbox).find('table tbody tr').length;

	// We'll plot the milestones at index N..1 as the Y axis increases upwards
	i = max_y;
	data = [];
	yaxis = [];

	$(rows).each(function(index, row) {
		
		// This is all the data we have about each milestone, load it...
		subject = $(row).find('td:eq(0)').text();
		is_open = $(row).find('td:eq(1)').text();
		starts = $(row).find('td:eq(2)').text();
		due = $(row).find('td:eq(3)').text();
		open_tasks = $(row).find('td:eq(4)').text();
		closed_tasks = $(row).find('td:eq(5)').text();
		open_points = $(row).find('td:eq(6)').text();
		closed_points = $(row).find('td:eq(7)').text();

		// Milestone subject is displayed on the left
		yaxis.push([i, subject]);

		// Plot milestone box from start -> finish, at index i
		data.push([
			[new Date(starts), i--, new Date(due), subject],
		]);

	});

	options = {
	    series:{ //editMode: 'v',editable:true, //TODO...
            gantt: {active:true,show:true,barHeight:.5 }
    	},
	    xaxis:{
			mode:"time",
			min: min_x,
			max: max_x,
    	},
		yaxis: {min: 0, max: max_y+1, ticks:yaxis},
    	grid:   { hoverable: true, clickable: true} //, editable: true} //TODO...
		
	};

	$(chartbox).plot(data, options);

	$(chartbox).bind("plotclick", function (event, pos, item) {
		foo = bar;
	});

});

