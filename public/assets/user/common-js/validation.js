$(function () {
    $('.input-decimal').on('keypress', function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    minimumDescriptionCharacters();
});

function checkRequiredFields(inputsRequired) {
    var isValid = true;

    for (let inputField of inputsRequired) {
        var inputElement = $(inputField);
        var inputValue;
        var inputType = 'normal';
        if (inputElement.hasClass('radio-inline')) {
            inputType = 'radio';
        }

        if (inputType == 'normal') {
            inputValue = inputElement.val();
            if (Array.isArray(inputValue) && inputValue.length == 0) {
                inputValue = '';
            }
        } else {
            inputValue = inputElement.find("input:checked");

            if (inputValue.length > 0) {
                inputValue = 1;
            } else {
                inputValue = null;
            }
        }

        if (!inputValue) {
            setError(inputElement);
            isValid = false;
        }
    }

    return isValid;
}

function setError(inputElement, errorMsg = null) {
    inputElement.closest('.form-group').find('.invalid-feedback').removeClass('d-block').addClass('d-none');
    inputElement.addClass('is-invalid');
    inputElement.closest('.form-group').find('.js-validation-error').removeClass('d-none').show();

    if (errorMsg) {
        inputElement.closest('.form-group').find('.js-validation-error').html('<strong>' + errorMsg + '</strong>');
    }
}

function minimumDescriptionCharacters() {
    $('.text-check-count').each(function(inputElement) {
        var maxLength = $(this).attr('minValidLength');
        var minLength = $(this).attr('minlength');
        $(this).next('.counter-status').find('.total-count').html(maxLength);
        $(this).next('.counter-status').find('.current-count').html($(this).val().length);
        
        $(this).on('change keyup', function() {
            var currentLength = $(this).val().length;
            $(this).next('.counter-status').find('.current-count').html(currentLength);
            if (minLength && currentLength < minLength) {
                $(this).next('.counter-status').find('.current-count').addClass('text-warning');
            } else {
                $(this).next('.counter-status').find('.current-count').removeClass('text-warning');
            }
        });
    })
    /* var characters_count = $('#nav-tabContent .tab-pane.active.show textarea').val().length;
    $('#char_count').text(characters_count+'/5000');
    if (characters_count < 300) {
        $('#alert_min_characters').show();
    } else {
        $('#alert_min_characters').hide();
    } */
}