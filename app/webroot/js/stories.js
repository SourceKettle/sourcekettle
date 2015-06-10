
// Story subject edit button
$('.story-subject-text').find(':button.edit').bind('click', function() {
	var subject = $(this).parent();
	subject.find('.story-subject-text').hide();
	subject.find('.edit-form').show();
	$(this).hide();
});

// Story description edit button
$('.story-description-text').find(':button.edit').bind('click', function() {
	var description = $(this).parent();
	description.find('.story-description-text').hide();
	description.find('.edit-form').show();
	$(this).hide();
});

// Story acceptance criteria edit button
$('.story-acceptance-criteria').find(':button.edit').bind('click', function() {
	var description = $(this).parent();
	description.find('.story-acceptance-criteria').hide();
	description.find('.edit-form').show();
	$(this).hide();
});
