jQuery(document).ready(function($) {

    "use strict";

    $(".usp-form-js").on('submit', (function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        let form = $(this);
        let url = form.attr('action');

        let response = form.find('.usp-submission-response');
        response.hide();

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                response = form.find('.usp-submission-response');
                response.text(data);
                response.show();
            }
        });


    }));
});