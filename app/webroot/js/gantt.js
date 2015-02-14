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

	// Current date
	var today = new Date();
	today.setHours(0);
	today.setMinutes(0);
	today.setSeconds(0);

	var milestoneUrl = chartbox.attr('data-milestone-url');
	var apiUrl = chartbox.attr('data-api-url');
	$(rows).each(function(index, row) {
		
		// This is all the data we have about each milestone, load it...
		var milestoneId = $(row).find('td:eq(0)').attr('data-milestone-id');
		var subject = $(row).find('td:eq(0)').text();
		var is_open = ($(row).find('td:eq(1)').text() == 'true');
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
		editMode: 'x',
		editable:true,
            	gantt: {active:true,show:true,barHeight:.5 }
    	    },
	    xaxis:{
			mode:"time",
			min: min_x,
			max: max_x,
    	},
		yaxis: {min: 0, max: max_y+1, ticks:yaxis},
    	grid:   {
		markings: [
			{color: '#f46', lineWidth: 3, xaxis: {from: today, to: today}}
		],
		clickable: true,
		editable: true
	}
		
	};

	chartbox.plot(data, options);

	// When clicking one of the milestones, go to the milestone view
	chartbox.bind("plotclick", function (event, pos, item) {
		if (item == null) {
			return;
		}
		var point = item.series.data[item.dataIndex];
		var milestoneId = point[4];
		window.location = milestoneUrl + '/' + milestoneId;
	});

	// When dragging out the milestone ends, change the start or end date
	chartbox.bind("datadrop", function(event, pos, item){

		if (item == null) {
			return;
		}

		if(!item.dataIndex.length) {
			return;
		}

		// data is the (original) milestone data - id, start/end date etc.
		var data = item.series.data[ item.dataIndex[0] ];
		var milestoneId = data[4];

		// Work out which end of the milestone we're changing
		if(item.dataIndex[1] == 1){
			which = 'starts';
		}
		else{
			which = 'due';
		}

		// Get the new date
		var newDate = new Date(parseInt(pos.x1));
		newDate = newDate.getUTCFullYear() + '-' + (newDate.getUTCMonth()+1) + '-' + newDate.getUTCDate();

		// Save milestone data
		var milestoneInfo = {
			'id' : milestoneId,
		};
		milestoneInfo[which] = newDate;

		
		$.ajax(apiUrl+"/"+milestoneId, {
			"dataType" : "json",
			"type" : "post",
			"data" : {Milestone: milestoneInfo},
			"success" : function (data) {
			},
			"error" : function () {
				alert("Failed to change milestone "+which+" date!");
			}
		});
	});

});

