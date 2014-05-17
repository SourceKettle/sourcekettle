


// Pull the values to plot out of the table
// The usernames are all in a <a> with a class of 'userlink',
// and the number of minutes is in the data-minutes attribute
// of the next <td> along the row.
var data = [];
$('#usertimes').find('.userlink a').each(function(index){
	username = $(this).text();
	userlink = this.href;
	timebox = $(this.parentNode.parentNode.nextSibling);
	timestring = timebox.text();
	minutes = timebox.attr('data-minutes');
	data.push({
		label: username,
		data: minutes
	});
});

$('#piechart').unbind();

// Generate colours based on the current theme, getting lighter
// http://www.benknowscode.com/2013/02/graphing-with-flot-controlling-series_9976.html
// Base it on the colour of a link, as that should look OK against the background.
//var base_colour = $(document.createElement('a')).css('color');
var base_colour = $($('.nav-list').find(':not(.active)li a')[0]).css('color');
var len = 7;
var colours = $.map( data, function ( o, i ) {
   return $.Color(base_colour).lightness(0.6-i/(len*1.2)).toHexString();
});

$.plot('#piechart', data, {

    series: {
        pie: {
            show: true,
			radius: 1,
			tilt: 0.4,

			// Show labels on any section which is big enough
			label: {
				show: true,
				radius: 1,
				threshold: 0.1,
				background: {
					opacity: 0.9,
					color: '#000'
				}
			}
        }
    },

	// We already have our own gravatar-ified legend with the data in
	legend: {
		show: false
	},

	// Hover/click to see details
	grid: {
        hoverable: true,
        clickable: true
    },

	colors: colours
});

$('#piechart').bind("plotclick", function(event, pos, obj) {

	if (!obj) {
		return;
	}

	var percent = parseFloat(obj.series.percent).toFixed(2);
	alert(obj.series.label+': '+percent+'%');
});

