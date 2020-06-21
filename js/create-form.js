/**
 * This js-file is specifically meant for the real-time form creator in the admin section.
 */
jQuery(document).ready(function($) {

    "use strict";

    // Popup Settings
    $('#is_popup').on('click', (function() {
        if (this.checked) {
            $('#is_popup_frequency_container').show();
        }
        else {
            $('#is_popup_frequency_container').hide();
        }
    }));

    let addedInputId = 0;

    // Category & Text editor
    $('#usp-btn-add-row').on('click', function() {
        addSubscriptionRow();
    });

    function addSubscriptionRow(subscriptionButton = null) {

        // Append table row
        addedInputId++;
        let tableRowId = "usp-id-" + addedInputId;

        let tableRowHtml;
        if (subscriptionButton === null) {
            tableRowHtml = '<tr><td> <select name="category_id[]">';
        }
        else {
            tableRowHtml = '<tr><td> <select name="category_id[]" value="' + subscriptionButton.category_id + '">';
        }

        let categoryDataText = $("#usp-category-data").text();
        let categoryData = JSON.parse(categoryDataText);
        for (let i = 0; i < categoryData.length; i++) {
            if (subscriptionButton !== null && subscriptionButton.category_id === categoryData[i].id) {
                tableRowHtml += '<option selected="selected" value="' + categoryData[i].id + '">' + categoryData[i].name + '</option>';
            }
            else {
                tableRowHtml += '<option value="' + categoryData[i].id + '">' + categoryData[i].name + '</option>';
            }
        }
        tableRowHtml += '</select><td>';

        // Create input boxes to enter the submission button text
        let buttonText = button_text_string.text;
        if (subscriptionButton === null) {
            tableRowHtml += '<input name="button_text[]" type="text" placeholder="' + buttonText + '" id="' + tableRowId + '" required></td></tr>';
        }
        else {
            tableRowHtml += '<input name="button_text[]" type="text" placeholder="' + buttonText + '" id="' + tableRowId + '" value="' + subscriptionButton.text  + '" required></td></tr>';
        }

        $('#usp-tbody-category-text').append(tableRowHtml);

        // Append button to realtime forms
        let buttonClass = "usp-button-" + addedInputId;
        let defaultButtonHtml = '<button class="' + buttonClass + '" type="submit">';
        if (subscriptionButton != null) { defaultButtonHtml += subscriptionButton.text; }
        defaultButtonHtml += '</button>';
        $('.usp-form-submit').each(function() {
            $(this).append(defaultButtonHtml);
        });


        $('#' + tableRowId).on('input', function() {
            let text = $('#' + tableRowId).val();
            $('.' + buttonClass).each(function() {
                $(this).text(text);
            });
            return false;
        });

        return false;
    }

    $('#usp-btn-delete-row').on('click', function() {
        $('#usp-tbody-category-text tr:last').remove();
        let buttonClass = "usp-button-" + addedInputId;
        $('.' + buttonClass).each(function() {
            $(this).remove();
        });

        addedInputId--;

        return false;
    });

    // --- Real Time Visualizer Updater --- //
    // Update title in real time
    $('#form-title').on('input', function() {
        let title = $('#form-title').val();
        $('.usp-form-title-text').each(function() {
            $(this).text(title);
        });
    });

    // Update body in real time
    $('#form-body').on('input', function() {
        let body = $('#form-body').val();
        $('.usp-form-body-text').each(function() {
            $(this).text(body);
        });
    });


    $('#form-skin').on('change', function() {
        let selectedSkin = this.value;

        for (let i = 0; i < this.length; i++) {
            let skin = this.options[i].value;
            $('#form-skin-' + skin).hide();
        }

        $('#form-skin-' + selectedSkin).show();
    });

    // EDITOR MODE
    if (usp_editor_data.length !== 0) {
        // add subscription buttons
        let subscriptionButtons = usp_editor_data.subscription_buttons;
        for (let i = 0; i < subscriptionButtons.length; i++) {
            let subscriptionButton = subscriptionButtons[i];
            addSubscriptionRow(subscriptionButton);
        }

        // update skin select
        $('#form-skin').attr("value", usp_editor_data.skin_name);
        $('#form-skin').trigger("change");

    }
    else {
        $('#usp-btn-add-row').trigger('click');
    }

});