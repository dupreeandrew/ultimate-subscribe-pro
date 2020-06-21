jQuery(document).ready(function($) {
    "use strict";

    $.ajax({
        type: "GET",
        url: window.location.href,
        data: usp_cookie_data, // serializes the form's elements.
        success: function(data) {
            // do nothing. it's just setting a cookie
        }
    });
});