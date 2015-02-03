var open = {};
var closed = {};

// JavaScript code to plot a gantt chart based on log data only
// Falls back to just showing a list of logged changes, which is less fun
$('.gantt-outer').each(function(index, outer) {

	var chartbox = $(outer).find('.gantt-chart');
	var controls = $(outer).find('.gantt-controls');

	// Hide the table of data
	chartbox.find('table').hide();

	// Row list... we'll use this a few times
	var rows = chartbox.find('table tbody tr');

	// Get the first start date and last due date as the X axis range
	var min_x = new Date($(rows[0]).find('td:eq(2)').text());
	var max_x = new Date($(rows[rows.length-1]).find('td:eq(3)').text());

	// Get the highest Y value i.e. number of milestones
	var max_y = chartbox.find('table tbody tr').length;

	// We'll plot the milestones at index N..1 as the Y axis increases upwards
	var i = max_y;
	var data = [];
	var yaxis = [];

	var milestoneUrl = chartbox.attr('data-milestone-url');
	var apiUrl = chartbox.attr('data-api-url');
	$(rows).each(function(index, row) {
		
		// This is all the data we have about each milestone, load it...
		var milestoneId = $(row).find('td:eq(0)').attr('data-milestone-id');
		var subject = $(row).find('td:eq(0)').text();
		var is_open = $(row).find('td:eq(1)').text();
		var starts = $(row).find('td:eq(2)').text();
		var due = $(row).find('td:eq(3)').text();
		var open_tasks = $(row).find('td:eq(4)').text();
		var closed_tasks = $(row).find('td:eq(5)').text();
		var open_points = $(row).find('td:eq(6)').text();
		var closed_points = $(row).find('td:eq(7)').text();

		// Milestone subject is displayed on the left
		yaxis.push([i, subject]);

		// Plot milestone box from start -> finish, at index i
		data.push([
			[new Date(starts), i--, new Date(due), subject, milestoneId],
		]);

	});

	options = {
	    series:{
		//editMode: 'x', // TODO
		//editable:true,
            	gantt: {active:true,show:true,barHeight:.5 }
    	    },
	    xaxis:{
			mode:"time",
			min: min_x,
			max: max_x,
    	},
		yaxis: {min: 0, max: max_y+1, ticks:yaxis},
    	grid:   { clickable: true}//, editable: true} //TODO...
		
	};

	chartbox.plot(data, options);

	chartbox.bind("plotclick", function (event, pos, item) {
		if (item == null) {
			return;
		}
		var point = item.series.data[item.dataIndex];
		var milestoneId = point[4];
		window.location = milestoneUrl + '/' + milestoneId;
	});

	// TODO
	/*
	chartbox.bind("datadrop", function(event, pos, item){
		if (item == null) {
			return;
		}

		var dI,data,fromLabel;
		if(item.dataIndex.length) {
			dI = item.dataIndex[0];
		} else {
			return;
		}

		data = item.series.data[dI];
		var milestoneId = data[4];

		var newDate;
		if(item.dataIndex[1] == 1){
			which = 'starts';
			newDate = new Date(data[0]);
		}
		else{
			which = 'due';
			newDate = new Date(data[2]);
		}
		var milestoneInfo = {
			'id' : milestoneId,
		};
		milestoneInfo[which] = newDate;
	});*/

});

