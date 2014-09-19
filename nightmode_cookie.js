$(function () {
	$("input.switch-input").each(function() {
		var mycookie = $.cookie($(this).attr('name'));
		if (mycookie && mycookie == "true") {
			$(this).prop('checked', mycookie);
		}
	});
	
	 $(document).on("change", "input.switch-input", function() {
		$.cookie($(this).attr("name"), $(this).prop('checked'), {
			path: '/',
			expires: 365
		});
	updateStylesheet();
	});
});
