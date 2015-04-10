
// Make the sidebar nav menu collapse-y
$('.collapse').collapse();

// Add nice bootstrap tooltips to anything that wants one
// DISABLED for now, as it's about 99.9% certain that the damn things will flicker annoyingly
/*$('*[title]').tooltip({
	delay: { "show": 0, "hide": 100 }
});*/

// distraction-free mode - hide the sidebar, topbar and title
function toggleDistractions() {
	if ($("#page-area").hasClass("fullpage")) {
		$("#page-area").removeClass("span12").removeClass("fullpage");
		$("#page-area").addClass("span10");
		$(".distractions").show();
	} else {
		$(".distractions").hide();
		$("#page-area").removeClass("span10");
		$("#page-area").addClass("span12").addClass("fullpage");
	}
}
