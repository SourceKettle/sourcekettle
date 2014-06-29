$(function(){
	$('#timesheet').dataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bInfo" : false,
		"bFilter" : false,
	});

	$('.tempo').tooltip({
		selector: 'th[rel=tooltip]'
	});

	$('.tempoBody').bind('click', function() {
		var a = $('#' + $(this).attr('data-toggle'));
	
		// Specific modal box does not exist, add time
		if (a.size() === 0) {
			// Set the correct date and task ID
			$('#addTimeModal').find('.dp1').val($(this).attr('data-date'));
			$('#addTimeModal').find('#TimeTaskId').val($(this).attr('data-taskid'));
	
			// Ensure the time and description boxes are emptied
			$('#addTimeModal').find('#TimeMins').val('');
			$('#addTimeModal').find('#TimeDescription').val('');

			// Show the add time box
			$('#addTimeModal').modal('show');

		// Show the drill-down of tasks
		} else {
			a.modal('show');
		}
	});
});
