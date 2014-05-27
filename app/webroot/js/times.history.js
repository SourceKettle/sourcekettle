$(function(){
	$('#timesheet').dataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bInfo" : false,
		"bFilter" : false,
	});

	$('.tempo').tooltip({
		selector: 'th[rel=tooltip]'
	})
	$('.tempoBody').bind('click', function() {
		var a = $('#' + $(this).attr('data-toggle'));
	
		$('.dp1').val($(this).attr('data-date'));
	
		var taskId = $(this).attr('data-taskId');
		$('option[value='+taskId+']').attr('selected', 'selected');
	
		if (a.size() === 0) {
			$('#addTimeModal').modal('show');
		} else {
			a.modal('show');
		}
	});
});
