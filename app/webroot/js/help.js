
// Make all links non-functional within example items
$(function(){
	$('.example').find('a').attr('href', '#');
	$('.example').find('a').attr('onclick', 'return false;');
	$('.example').find('.task-container').attr('onclick', 'return false;');
});
