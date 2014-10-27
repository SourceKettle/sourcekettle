// JavaScript code to plot a burndown chart based on log data only
// Falls back to just showing a list of logged changes, which is less fun
$('.burndown').each(function(index, chartbox) {

	// Hide the table of data
	$(chartbox).find('table').hide();

	// Pull the values to plot out of the list
	var open   = {
		'tasks'  : {'label' : 'Pending tasks', 'data' : []},
		'points' : {'label' : 'Pending story points', 'data' : []},
		'hours'  : {'label' : 'Pending time (estimated hours)', 'data' : []}
	};
	var closed   = {
		'tasks'  : {'label' : 'Finished tasks', 'data' : []},
		'points' : {'label' : 'Finished story points', 'data' : []},
		'hours'  : {'label' : 'Finished time (estimated hours)', 'data' : []}
	};

	$(chartbox).find('table tbody tr').each(function(index, row) {
		timestamp = $(row).find('td:eq(0)').text();
		open['tasks']['data'].push([timestamp, $(row).find('td:eq(1)').text()]);
		open['points']['data'].push([timestamp, $(row).find('td:eq(3)').text()]);
		open['hours']['data'].push([timestamp, $(row).find('td:eq(5)').text()/60]);
		closed['tasks']['data'].push([timestamp, $(row).find('td:eq(2)').text()]);
		closed['points']['data'].push([timestamp, $(row).find('td:eq(4)').text()]);
		closed['hours']['data'].push([timestamp, $(row).find('td:eq(6)').text()/60]);
	});

	$.plot(chartbox, [
		open['tasks'],
		//open['points'],
		//open['hours'],
		closed['tasks'],
		//closed['points'],
		//closed['hours']
	], {
        'xaxis' : {'mode' : 'categories'},
        'series' : {
            'stack' : 'stack',
            'lines' : {
                'fill' : true,
            }
        }
    });

});

function plotAccordingToChoices() {

	var data = [];

	var choiceContainer = $(chartbox).find(".choices");
	choiceContainer.find("input:checked").each(function () {
		var key = $(this).attr("name");
		if (key && datasets[key]) {
			data.push(datasets[key]);
		}
	});

	if (data.length > 0) {
		$.plot("#placeholder", data, {
			yaxis: {
				min: 0
			},
			xaxis: {
				tickDecimals: 0
			}
		});
	}
}
