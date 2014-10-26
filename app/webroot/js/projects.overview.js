/**
*
* JS for the projects overview page.
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     SourceKettle Development Team 2012
* @link          http://github.com/SourceKettle/sourcekettle
* @package       SourceKettle.webroot.js
* @since         SourceKettle v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

$(document).ready(function(){
  $('#view_more_button a').click(function(){
    $('#project_description').html($('#full_description').html());
  });
});


// Pull the values to plot out of the list
var data = [];
var i = 0;
var len = 4;
//var base_colour = $(document.createElement('a')).css('color');
// Find a suitable navbar link and use its colour as the base
var base_colour = $($('.nav-list').find(':not(.active)li a')[0]).css('color');
$('#taskcounts').find('li').each(function(index){
	statusname = $(this).attr('data-taskstatus');
	numtasks   = $(this).attr('data-numtasks');
	colour = $.Color(base_colour).lightness(0.6-i/(len*1.2)).toHexString();
	$(this).find('a').css('color', colour);
	data.push({
		label: statusname,
		data: numtasks,
		color: colour
	});
	i++;
});

$('#piechart').unbind();


$.plot('#piechart', data, {

    series: {
        pie: {
            show: true,
			radius: 1,

			// Show labels on any section which is big enough
			label: {
				show: false
			}
        }
    },

	// We already have our own legend with the data in
	legend: {
		show: false
	},

	// Hover/click to see details
	grid: {
        hoverable: true,
        clickable: true
    },

	//colors: colours
});

$('#piechart').bind("plotclick", function(event, pos, obj) {

	if (!obj) {
		return;
	}

	var percent = parseFloat(obj.series.percent).toFixed(2);
	alert(obj.series.label+': '+percent+'%');
});

