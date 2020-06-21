jQuery(document).ready(function($) {

    "use strict";

    // changes the input fields if a user selects an email preset like Gmail, Ymail, etc..
    $("#usp-smtp-profile-js").on('change', function() {

        let selectedProfile = this.value;

        let smtpServerInput = $("#usp-smtp-server-js");
        let smtpPortInput = $("#usp-smtp-port-js");
        let smtpEncryptionInput = $("#usp-smtp-encryption-js");

        if (selectedProfile === "none") {
            smtpServerInput.val("");
            smtpPortInput.val("");
            $("#usp-smtp-password-js").val("");
            $("#usp-smtp-custom-settings").hide();

            smtpServerInput.prop("required", false);
            smtpPortInput.prop("required", false);
            return;
        }

        $("#usp-smtp-custom-settings").show();

        if (selectedProfile === "gmail") {
            smtpServerInput.val("smtp.gmail.com");
            smtpServerInput.prop("readonly", true);

            smtpPortInput.val("587");
            smtpPortInput.prop("readonly", true);

            smtpEncryptionInput.val("tls");
            smtpEncryptionInput.prop("disabled", true);

        }
        else if (selectedProfile === "ymail") {
            smtpServerInput.val("smtp.mail.yahoo.com");
            smtpServerInput.prop("readonly", true);

            smtpPortInput.val("587");
            smtpPortInput.prop("readonly", true);

            smtpEncryptionInput.val("tls");
            smtpEncryptionInput.prop("disabled", true)
        }
        else if (selectedProfile === "custom") {
            //smtpServerInput.val("");
            smtpServerInput.prop("readonly", false);
            smtpServerInput.prop("required", true);

            //smtpPortInput.val("");
            smtpPortInput.prop("readonly", false);
            smtpPortInput.prop("required", true);

            // smtpEncryptionInput.val("ssl");
            smtpEncryptionInput.prop("disabled", false)
        }
    });
    $("#usp-smtp-profile-js").trigger("change");

    $("#usp-form-email-settings").on('submit', (function( event ) {
        let smtpEncryptionInput = $("#usp-smtp-encryption-js");
        smtpEncryptionInput.prop("disabled", false);
    }));


});