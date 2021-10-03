<script>
var isSeller = !!"{{ $isSeller }}";
var isProfileWizard = !!"{{ $mode == 'profile-wizard' }}";
var defaultLang = "{{ $defaultLang }}";
var isFirstLogin = !!"{{ $is_first_login }}";

@if ($isSeller || $mode == 'profile-wizard')
    var profCatList = @json($professionCatList);
    var professions = @json(json_decode(old('professions', null))) || @json($userProfessions);
@endif

var current_step = 1;
var last_navigated_step = current_step - 1;
var MAX_NAVIGATE_STEP = 5;

$(function () {
    if (isFirstLogin) {
        $('#welcome-modal').modal();
    }

    if (isSeller || isProfileWizard) {
        KTSelect2.init();
        KTTagify.init();
        KTBootstrapMultipleSelectsplitter.init();

        $('#btn-add-prof').on('click', onAddProfession);
        $('#select-profession-category').on('change', onChangeProfCategory);
        $('input[type=radio][name=enroll_type]').on('change', onSelectEnrollType);
    }

    $('#photo-input').on('change', onChangePhoto);
});

function onNextProfileStep() {
    if (current_step < MAX_NAVIGATE_STEP) {
        navigateStep(current_step + 1);
    }
}

function onPreviousProfileStep() {
    if (current_step >= 2) {
        navigateStep(current_step - 1);
    }
}

function navigateStep(targetStep) {
    if (targetStep > current_step && !validateForm()) {
        return false;
    }

    if (last_navigated_step + 2 == targetStep) {
        last_navigated_step = current_step;
    }

    //initialize steps
    $('.bs-vertical-wizard ul li').removeClass();
    $('.bs-vertical-wizard ul li .fa-check').addClass('d-none');

    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('current-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').hide();

    // set step marks
    $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ')').removeClass().addClass('current');
    $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ') .fa-check').addClass('d-none');
    $('.bs-vertical-wizard ul li:lt(' + last_navigated_step + ')').addClass('complete');
    $('.bs-vertical-wizard ul li:lt(' + last_navigated_step + ') .fa-check').removeClass('d-none');

    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').eq(targetStep - 1).addClass('current-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('completed-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').slice(0, targetStep - 1).addClass('completed-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').slice(0, targetStep - 1).show();

    if (targetStep == 1) {
        $('#previous-profile-step').hide();
    } else {
        $('#previous-profile-step').show();
    }

    if (targetStep == 4) {
        $('#next-profile-step').hide();
        $('#complete-profile-step').show();
    } else {
        $('#next-profile-step').show();
        $('#complete-profile-step').hide();
    }

    $("#profile-form-step" + current_step).fadeOut(function() {
        $("#profile-form-step" + targetStep).fadeIn(function() {
        });
    });

    window.scrollTo(0, 0);
    
    current_step = targetStep;
    return true;
}

function validateForm()
{
    // initialize all previous steps
    $('.js-validation-error').addClass('d-none').hide();
    $('.is-invalid').removeClass('is-invalid');

    var isValid = false;
    switch (current_step) {
        case 1:
            isValid = validateStep1();
            break;
        case 2:
            isValid = validateStep2();
            break;
        case 3:
            isValid = validateStep3();
            break;
        case 4:
            isValid = validateStep4();
            break;
        default:
            break;
    }

    return isValid;
}

function validateStep1() {
    var isValid = true;
    var inputsRequired;
    if (isSeller) {
        inputsRequired = ['select[name=country_id]', '#sel-language', '#input-professions'];
    } else {
        inputsRequired = ['select[name=country_id]'];
    }
    
    isValid = checkRequiredFields(inputsRequired);
    return isValid;
}

function validateStep2() {
    var isValid = true;
    var inputsRequired = ['.enroll-type'];
    isValid = checkRequiredFields(inputsRequired);

    if (!isValid) {
        return false;
    }

    var enrollType = $(".enroll-type input[type=radio]:checked").val();
    if (enrollType != 4) {
        inputsRequired = ['select[name=association_id]', 'input[name=city]'];
        isValid = checkRequiredFields(inputsRequired);
    }

    return isValid;
}

function validateStep3() {
    var inputsRequired = ['textarea[name=description_' + defaultLang + ']'];
    var isValid = checkRequiredFields(inputsRequired);

    if (isValid) {
        if ($('textarea[name=description_' + defaultLang + ']').val().length < 300) {
            setError($('textarea[name=description_' + defaultLang + ']'), "{{ trans('main.Description must be at least 300 characters') }}");
            isValid = false;
        }
    } else {
        setError($('textarea[name=description_' + defaultLang + ']'), "{{ trans('main.Enter Description') }}");
    }

    return isValid;
}

function validateStep4() {
    var inputsRequired = ['input[name=photo]'];
    var isValid = checkRequiredFields(inputsRequired);

    return isValid;
}

function onSubmitForm(event) {
    event.preventDefault();

    if (validateStep4()) {
        $(event.target).closest('form').submit();
    }
}

if (isSeller || isProfileWizard) {
    
    var KTSelect2 = function () {
        return {
            init: function () {
                $('#sel-language').select2({
                    placeholder: "{{ trans('main.select language') }}",
                });

                setTimeout(() => {
                    $('.select2-selection--multiple input').attr('autocomplete', 'new-password');
                }, 100);
            }
        };
    }();

    var tagify;
    var KTTagify = function () {
        return {
            init: function () {
                var input = document.getElementById('input-professions');

                tagify = new Tagify(input);

                if (professions && professions.length) {
                    for (prof of professions) {
                        tagify.addTags([{
                            value: prof.value,
                            category_id: prof.category_id,
                            profession_id: prof.profession_id
                        }]);
                    }
                }
            }
        };
    }();

    var KTBootstrapMultipleSelectsplitter = function () {
        return {
            // public functions
            init: function () {
                // $('#select-profession').multiselectsplitter();
            }
        };
    }();
}

function onAddProfession() {
    if (tagify.value.length >= 10) {
        alert("{{ trans('main.You can enter up to 10 Professions') }}");
    } else {
        var profText = $('#select-profession option:selected').text();
        var categoryId = $('#select-profession-category').val();
        var professionId = $('#select-profession').val();

        if (!categoryId) {
            alert("{{ trans('main.Please select category and then select profession') }}");
            return;
        }

        if (!professionId || profText == '') {
            alert("{{ trans('main.Please select the profession') }}");
            return;
        }

        tagify.addTags([{
            value: profText,
            category_id: categoryId,
            profession_id: professionId
        }]);
    }

    $('#professionModal').modal('toggle');
}

function onChangeProfCategory() {
    var catId = $('#select-profession-category').val();
    $('#select-profession').empty();
    $('#select-profession').append('<option class="select-placeholder" value="">' + "{{ trans('main.Select a profession') }}" + '</option>');
    for (let prof of profCatList[catId]) {
        $('#select-profession').append('<option value=' + prof.id + '>' + prof.name + '</option>')
    }
    $('#select-profession').val('');
}

function onChangePhoto() {
    const url = "/profile/upload/photo";

    data = new FormData();
    data.append('photo', $('#photo-input')[0].files[0]);

    ajax_post(url, data, function (res) {
        $('#photo-file-name').val(res.data);
        $('.image-input-wrapper').css('background-image', 'url(/upload/user/' + res.data + ')');
    }, 'multipart/form-data');
}

function openProfessionDlg() {
    // $('#form-profession')[0].reset();
    $('#form-profession select[data-multiselectsplitter-firstselect-selector]').val('');
    $('#form-profession select[data-multiselectsplitter-secondselect-selector]').val('');
    $('#select-profession').val('');
    $('#professionModal').modal('toggle');
}

var ENROLL_TYPE_NOT_ENROLLED = 4;

function onSelectEnrollType() {
    if (this.value == ENROLL_TYPE_NOT_ENROLLED) {
        $('.order-detail-container').addClass('d-none');
    } else {
        $('.order-detail-container').removeClass('d-none');
    }
}
</script>