$(function(){
	var cache = {};
		$('input.typeahead').typeahead({
        items: 5,
        minLength: 1,
        source: function (query, process) {
			url = $(this.$element).attr('data-api-url');
			name = $(this.$element).attr('data-api-name');
            $.get(url, { query: query }, function (data) {
                process($.parseJSON(data)[name]);
            });
        }
    });
});
