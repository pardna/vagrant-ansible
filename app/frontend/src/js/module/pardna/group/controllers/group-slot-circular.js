(function() {
	var num = "";
	for (var n = 0; n < 61; n++) {
		num += "<div>" + n + "</div>";
	}

	var i = 50;
	for (var a = 0; a < i; a++) {
		$('.group-details__slots').append("<div class='" + (a == 0 ? 'group-details__slots' : '') + "' >" + (a == 0 ? num : '') + "</div>");
	}
})();