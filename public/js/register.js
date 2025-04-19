$(document).ready(function(){
    // mobile field validation and api for country code
    const $phoneInputField = $("#fullPhoneNumber");
    const phoneInput = window.intlTelInput($phoneInputField[0], {
        initialCountry: "IN", // Automatically detect user's country
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" // for formatting
    });

    const $errorMsg = $("#error-msg");
    const $registerForm = $('#register-form');

    $registerForm.on("submit", function(e) {
        e.preventDefault();

        if (!phoneInput.isValidNumber()) {
            $errorMsg.show();
            return;
        } else {
            $errorMsg.hide();
        }

        const countryCode = phoneInput.getSelectedCountryData().dialCode;

        // Get the full phone number in E.164 format
        const fullPhoneNumber = phoneInput.getNumber();

        // Set the hidden fields
        $("#countryCode").val(countryCode);
        $("#fullPhoneNumber").val($phoneInputField.val());

    });
})
