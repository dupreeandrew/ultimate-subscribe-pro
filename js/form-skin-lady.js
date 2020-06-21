jQuery(document).ready(function($) {

    "use strict";

	$('.usp-lady-form-holder').delegate("input", "focus", function(){
		$('.usp-lady-form-holder').removeClass("usp-lady-active");
		$(this).parent().addClass("usp-lady-active");
	})
})