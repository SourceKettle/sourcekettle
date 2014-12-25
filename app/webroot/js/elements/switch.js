// Initialise switches
$('input[type="checkbox"].switch').bootstrapSwitch().on('switch-change', function(event, data){
    id = $(data.el).attr('id');
    sectionHide = $(data.el).attr('data-section-hide');
    url = $(data.el).attr('data-setting-url');
    name = $(data.el).attr('data-setting-name');
    state = data.value;

    postData = {};
    postData['data['+name.replace(/\./g, '][')+']'] = state;

    $.ajax(url, {
		"data" : postData,
		"dataType" : "json",
        "type" : "post",
        "context" : $(data.el),
		"success" : function (data) {
	        if (sectionHide) {
	            if (state) {
	                $('#'+sectionHide).show();
	            } else {
	                $('#'+sectionHide).hide();
	            }
	        }
        }
    });

});

// Hide any sections that should be hidden by an 'off' switch
$('input[type="checkbox"].switch').each(function(idx, sw){
    state = $(sw).attr("data-setting-value");
    section = $(sw).attr("data-section-hide");
    if (section && state == 0) {
        $("#"+section).hide();
    }
});
