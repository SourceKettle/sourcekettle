
// Make all links non-functional within example items
$(function(){
	$('.example a').attr('href', '#');
	$('.example a').attr('onclick', 'return false;');
});
