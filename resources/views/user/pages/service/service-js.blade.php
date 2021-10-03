@php
    use App\Models\Service;
@endphp

<script>
    const PROVIDE_ONLINE_TYPE = {
        ONLINE: 1,
        OFFLINE: 2
    };

    const ONSITE_TYPE = {
        ONSITE: 1,
        OFFSITE: 2,
        ONOFFSITE: 3
    };

    const CLIENT_PAYMENT_TYPE = {
        ONLINE: 1,
        ONSITE: 2,
        ONLINE_OR_ONSITE: 3,
        FREE: 4,
    };

    const extra_price_type = {
        EXTRA_NO: 1,
        EXTRA_FIX: 2,
        EXTRA_KILOMETER: 3,
    };

    var language = "{{ app()->getLocale() }}";
    var fromProfileWizard = "{{ session()->get('from_wizard') }}";

    var MAX_NAVIGATE_STEP = 5;
    var currentStep = 1;
    var lastNavigatedStep = currentStep - 1;
    var officeCount = "{{ $officeCount }}";
    var selectedOffices = officeCount > 1 ? @json(array_keys($selectedOffices)) : ["{{ $offices[0]['id'] }}"];
    var step2OfficeCompleted = 0;
    const subCategories = @json($subCategories);
    var photo = "{{ old('photo', $service->photo) }}";

    $(function() {
        $('#service-form-step' + currentStep).show();
        $('.wizard-step-link').on('click', onNavigateStep);
        $('#sel-category-id').on('change', onSelectCategory);
        $('input[name=provide_online_type]').on('change', onSelectServiceProvideType);
        $('.onsite_type input[type=radio]').on('change', onChangeProvideOnsiteType);
        $('input[name=client_payment_type]').on('change', onChangeClientPaymentType);
        $('input[name=confirm_first_service_book]').on('change', onChangeConfirmOption);
        $('select[name=online_office_id]').on('change', onSelectOnlineOffice);
        
        $('.extra_price_type input[type=radio]').on('change', onChangeExtraCostType);

        initPhotoInput();
        initOptionsContainer();
        // testSetting();
    });

    function testSetting() {
        if("{{ env('APP_ENV') }}" == 'local') {
            $('input[name=provide_online_type]').last().trigger('click');
            navigateStep(currentStep);
        }
    }

    function initOptionsContainer() {
        var provideOnlineType = $('.provide_online_type input[type=radio]:checked').val();
        selectServiceProvideType(provideOnlineType);

        var paymentType = $('.client_payment_type input:checked').val();
        changeClientPaymentType(paymentType);

        var extraCostType = $('.extra_price_type input:checked').val();
        changeExtraCostType(extraCostType);

        var onsiteType = $('.onsite_type input:checked').val();
        changeProvideOnsiteType(onsiteType);

        var onlineOfficeId = $('select[name=online_office_id]').val();
        selectOnlineOffice(onlineOfficeId);
    }

    function onNextNavigateStep() {
        if (currentStep < MAX_NAVIGATE_STEP) {
            if (navigateStep(currentStep + 1)) {
               $('#btn-post-later').addClass('d-none');
            }
        }
    }

    function onPreviousNavigateStep() {
        if (currentStep >= 2) {
            navigateStep(currentStep - 1);
        }
    }

    function onNavigateStep() {
        var targetStep = $(this).data('step');
        if (targetStep <= (lastNavigatedStep + 1)) {
            navigateStep(targetStep);
        }
    }

    function navigateStep(targetStep, from_step2_office_completed = false) {
        if (officeCount > 1) {
            hideStep2OfficeForm();
        }

        if (targetStep > currentStep && !validateForm()) {
            return false;
        }

        // when navigate from step2 to step3, if offline and one or more office it starts navigate office steps
        if (currentStep == 2 && targetStep == 3
            && $('.provide_online_type input:checked').val() == PROVIDE_ONLINE_TYPE.OFFLINE
            && officeCount > 1 && !from_step2_office_completed)
        {
            showStep2OfficeForm(0);
            return false;
        }

        if (currentStep == 3 && targetStep == 2
            && $('.provide_online_type input:checked').val() == PROVIDE_ONLINE_TYPE.OFFLINE
            && officeCount > 1)
        {
            showStep2OfficeForm(selectedOffices.length - 1);
        }

        if (targetStep == 3) {
            setStep3VisibleOptions();
        }

        if (lastNavigatedStep + 2 == targetStep) {
            lastNavigatedStep = currentStep;
        }

        //initialize steps
        $('.bs-vertical-wizard ul li').removeClass();
        $('.bs-vertical-wizard ul li .fa-check').hide();

        $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('current-step');
        $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').hide();
        
        // set step marks
        $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ')').removeClass().addClass('current');
        $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ') .fa-check').hide();
        $('.bs-vertical-wizard ul li:lt(' + lastNavigatedStep + ')').addClass('complete');
        $('.bs-vertical-wizard ul li:lt(' + lastNavigatedStep + ') .fa-check').show();

        $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').eq(targetStep - 1).addClass('current-step');
        $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('completed-step');
        $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').slice(0, targetStep - 1).addClass('completed-step');
        $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').slice(0, targetStep - 1).show();

        if (targetStep == 1) {
            $('#btn-previous').hide();
            if (fromProfileWizard) {
                $('#go-complete-profile').show();
            }
        } else {
            $('#btn-previous').show();
            $('#go-complete-profile').hide();
        }

        if (targetStep == 5) {
            $('.next-button').hide();
            $('.submit-container').show();
        } else {
            $('.next-button').show();
            $('.submit-container').hide();
        }
        
        $("#service-form-step" + currentStep).fadeOut(function() {
            $("#service-form-step" + targetStep).fadeIn();
        });
        window.scrollTo(0, 0);

        currentStep = targetStep;
        return true;
    }

    function validateForm()
    {
        // initialize all previous steps
        $('.js-validation-error').addClass('d-none').hide();
        $('.is-invalid').removeClass('is-invalid');

        var isValid = false;
        switch (currentStep) {
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
            case 5:
                isValid = validateStep5();
                break;
            default:
                break;
        }

        return isValid;
    }

    function validateStep1() {
        var inputsRequired = ['input[name=name_' + language + ']', '#sel-category-id', '#sel-sub-category-id',
            'textarea[name=description_' + language + ']'];
        var isValid = checkRequiredFields(inputsRequired);

        var descElement = $('textarea[name=description_' + language + ']');
        var desc = descElement.val();
        if (!desc) {
            setError(descElement, "{{ trans('main.Enter a service description') }}");
        } else if (desc.length < 100) {
            setError(descElement, "{{ trans('main.Description must be at least "00" characters', ['min' => 100]) }}");
            isValid = false;
        }

        return isValid;
    }

    function validateStep2() {
        var inputsRequired = ['.provide_online_type'];

        var isValid = true;
        if (!checkRequiredFields(inputsRequired)) {
            return false;
        }

        var provideOnline = $('.provide_online_type input:checked').val();
        if (provideOnline == PROVIDE_ONLINE_TYPE.ONLINE) {
            inputsRequired = [
                'select[name=online_office_id]',
                'select[name=online_delivery_time]',
                'select[name=online_revision]'
            ];
            isValid = checkRequiredFields(inputsRequired);
        } else {
            inputsRequired = [
                'select[name=duration]'
            ];

            if (officeCount <= 1) {
                var firstOfficeForm = '.office-detail-form-' + selectedOffices[0];
                inputsRequired.push(firstOfficeForm + ' .onsite_type');

                var onsiteType = $(firstOfficeForm + ' .onsite_type input[type=radio]:checked').val();
                if (onsiteType != ONSITE_TYPE.ONSITE) {
                    inputsRequired.push(firstOfficeForm + ' .provide-range-input');
                }
            }

            isValid = checkRequiredFields(inputsRequired);

            if (officeCount > 1 && selectedOffices.length < 1) {
                isValid = false;
                $('.activate-office-container .js-validation-error').removeClass('d-none').show();
            }
        }

        return isValid;
    }

    function validateStep3() {
        var isValid = true;
        var onlineType = $('input[name=provide_online_type]:checked').val();
        var price = 0;
        var discountSingle = 0;
        var discountMultiple = 0;

        if(onlineType == PROVIDE_ONLINE_TYPE.ONLINE) {
            var inputsRequired = ['.online_file_required'];

            if (!checkRequiredFields(inputsRequired)) {
                isValid = false;
            }

            inputsRequired = ['input[name=online_price]']
            if (!checkRequiredFields(inputsRequired)) {
                isValid = false;
                setError($('input[name=online_price]'), "{{ trans('main.Enter Price') }}");
            } else {
                price = $('input[name=online_price]').val();
                discountSingle = $('input[name=online_discount_percentage_single]').val();
                discountMultiple = $('input[name=online_discount_percentage_multiple]').val();
                discountSingle = discountSingle ? parseInt(discountSingle) : discountSingle;
                discountMultiple = discountMultiple ? parseInt(discountMultiple) : discountMultiple;

                $('input[name=online_discount_percentage_single]').val(discountSingle);
                $('input[name=online_discount_percentage_multiple]').val(discountMultiple);

                if (price < 5) {
                    isValid = false;
                    setError($('input[name=online_price]'), "{{ trans('main.Please enter greater than 5') }}");
                }

                if (discountSingle) {
                    if (discountSingle < 5) {
                        isValid = false;
                        setError($('input[name=online_discount_percentage_single]'), "{{ trans('main.Please enter greater than 5') }}");
                    } else {
                        var actualPrice = price - price / 100 * discountSingle;
                        if (actualPrice < 5) {
                            isValid = false;
                            setError($('input[name=online_discount_percentage_single]'), "{{ trans('main.Please enter smaller discount.') }}");
                        }
                    }
                }

                if (isMultipleService() && discountMultiple) {
                    if (discountMultiple < 5) {
                        isValid = false;
                        setError($('input[name=online_discount_percentage_multiple]'), "{{ trans('main.Please enter greater than 5') }}");
                    } else {
                        var actualPrice = price - price / 100 * discountMultiple;
                        if (actualPrice < 5) {
                            isValid = false;
                            setError($('input[name=online_discount_percentage_multiple]'), "{{ trans('main.Please enter smaller discount.') }}");
                        }
                    }
                }

                if (discountSingle && discountMultiple && (discountSingle >= discountMultiple)) {
                    isValid = false;
                    setError($('input[name=online_discount_percentage_multiple]'), "{{ trans('main.Discount multiple should be larger than single') }}");
                }
            }
        } else {
            var inputsRequired = ['.client_payment_type'];
            if (!checkRequiredFields(inputsRequired)) {
                return false;
            }

            var clientPayType = $('.client_payment_type input:checked').val();
            if (clientPayType == CLIENT_PAYMENT_TYPE.ONLINE
                || clientPayType == CLIENT_PAYMENT_TYPE.ONLINE_OR_ONSITE)
            {
                inputsRequired = ['.confirm_first_service_book'];
                isValid = checkRequiredFields(inputsRequired) && isValid;
            }

            if (clientPayType != CLIENT_PAYMENT_TYPE.FREE) {
                inputsRequired = ['input[name=offline_price]'];

                if (isAvailableOffsite()) {
                    inputsRequired.push('.extra_price_type');

                    var extraCostType = $('.extra_price_type input[type=radio]:checked').val();
                    if (extraCostType == 2 || extraCostType == 3) {
                        inputsRequired.push('input[name=extra_price]');
                    }
                }

                isValid = checkRequiredFields(inputsRequired) && isValid;
            }

            price = $('input[name=offline_price]').val();
            discountSingle = $('input[name=offline_discount_percentage_single]').val();
            discountMultiple = $('input[name=offline_discount_percentage_multiple]').val();
            discountSingle = discountSingle ? parseInt(discountSingle) : discountSingle;
            discountMultiple = discountMultiple ? parseInt(discountMultiple) : discountMultiple;

            $('input[name=offline_discount_percentage_single]').val(discountSingle);
            $('input[name=offline_discount_percentage_multiple]').val(discountMultiple);

            if (clientPayType != CLIENT_PAYMENT_TYPE.FREE) {
                if (price < 5) {
                    isValid = false;
                    setError($('input[name=offline_price]'), "{{ trans('main.Please enter greater than 5') }}");
                }

                if (discountSingle) {
                    if (discountSingle < 5) {
                        isValid = false;
                        setError($('input[name=offline_discount_percentage_single]'), "{{ trans('main.Please enter greater than 5') }}");
                    } else {
                        var actualPrice = price - price / 100 * discountSingle;
                        if (actualPrice < 5) {
                            isValid = false;
                            setError($('input[name=offline_discount_percentage_single]'), "{{ trans('main.Please enter smaller discount.') }}");
                        }
                    }
                }

                if (isMultipleService() && discountMultiple) {
                    if (discountMultiple < 5) {
                        isValid = false;
                        setError($('input[name=offline_discount_percentage_multiple]'), "{{ trans('main.Please enter greater than 5') }}");
                    } else {
                        var actualPrice = price - price / 100 * discountMultiple;
                        if (actualPrice < 5) {
                            isValid = false;
                            setError($('input[name=offline_discount_percentage_multiple]'), "{{ trans('main.Please enter smaller discount.') }}");
                        }
                    }
                }

                if (discountSingle && discountMultiple && (discountSingle >= discountMultiple)) {
                    isValid = false;
                    setError($('input[name=offline_discount_percentage_multiple]'), "{{ trans('main.Discount multiple should be larger than single') }}");
                }
            }
        }

        return isValid;
    }

    function validateStep4() {
        var inputsRequired = ['.has_video_call'];
        var isValid = checkRequiredFields(inputsRequired);

        return isValid;
    }

    function validateStep5() {
        return true;
    }

    function onSelectServiceProvideType(event) {
        selectServiceProvideType(event.target.value);
    }

    function selectServiceProvideType(serviceProvideType) {
        if (serviceProvideType) {
            if (serviceProvideType == PROVIDE_ONLINE_TYPE.ONLINE) {
                $('.online-option-container').show();
                $('.offline-option-container').hide();
                $('.online-service-office-form').show();
                $('.offline-service-office-form').hide();
                $('.step2-offices-container').hide();
            } else {
                $('.online-option-container').hide();
                $('.offline-option-container').show();
                $('.online-service-office-form').hide();
                $('.offline-service-office-form').show();
                $('.step2-offices-container').show();
            }
        }
    }

    function onSelectOnlineOffice(event) {
        selectOnlineOffice(event.target.value);
    }

    function selectOnlineOffice(officeId) {
        if (officeId) {
            $('.online-service-detail').show();
        } else {
            $('.online-service-detail').hide();
        }
    }

    function onChangeProvideOnsiteType(event) {
        var onsiteType = event.target.value;
        changeProvideOnsiteType(onsiteType);
    }

    function changeProvideOnsiteType(onsiteType)
    {
        if (onsiteType == ONSITE_TYPE.OFFSITE
            || onsiteType == ONSITE_TYPE.ONOFFSITE)
        {
            $('.provide-range-container').show();
        } else {
            $('.provide-range-container').hide();
        }
    }

    function onChangeClientPaymentType(event) {
        var paymentType = event.target.value;
        changeClientPaymentType(paymentType);
    }

    function changeClientPaymentType(paymentType) {
        if( !paymentType) {
            return;
        }

        if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE) {
            $('.confirm-service-book-container').show();
            $('.discount-container').show();
        } else if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE_OR_ONSITE) {
            $('.confirm-service-book-container').show();
            $('.discount-container').show();
            $('.client-pay-type-desc .desc-content').html("{{ trans('main.For each booking with payment on-site you will receive an e-mail to confirm booking. A fee for each booking confirmed will be taken.') }}");
        } else {
            $('.confirm-service-book-container').hide();
            $('.discount-container').hide();
            $('.client-pay-type-desc .desc-content').html("{{ trans('main.For each booking with payment on-site you will receive an e-mail to confirm booking. A fee for each booking confirmed will be taken.') }}");
        }

        changePaymentTypeDescription();

        setVisibleDiscountMultiple();

        if (paymentType == CLIENT_PAYMENT_TYPE.FREE) {
            $('.service-price-container').hide();
        } else {
            $('.service-price-container').show();
        }
    }

    function changePaymentTypeDescription()
    {
        var paymentType = $('input[name=client_payment_type]:checked').val();
        var confirmType = $('input[name=confirm_first_service_book]:checked').val();

        if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE) {
            if (confirmType == "{{ Service::BOOKING_CONFIRM }}") {
                $('.client-pay-type-desc .desc-content').html("{{ trans('main.Pay online Booking with confirmation') }}");
            } else {
                $('.client-pay-type-desc .desc-content').html("{{ trans('main.Pay online') }}");
            }
        } else {
            $('.client-pay-type-desc .desc-content').html("{{ trans('main.For each booking with payment on-site you will receive an e-mail to confirm booking. A fee for each booking confirmed will be taken.') }}");
        }

        if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE) {
            if (confirmType == "{{ Service::BOOKING_CONFIRM }}") {                
                $('.confirm-first-book-desc .desc-content').html("{{ trans('main.For each booking you will always receive an e-mail to confirm the appointment.') }}");
            } else if (confirmType == "{{ Service::BOOKING_DIRECTLY }}") {
                $('.confirm-first-book-desc .desc-content').html("{{ trans('main.Direct booking') }}");
            }
        } else if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE_OR_ONSITE) {
            if (confirmType == "{{ Service::BOOKING_CONFIRM }}") {                
                $('.confirm-first-book-desc .desc-content').html("{{ trans('main.For each booking you will always receive an e-mail to confirm the appointment.') }}");
            } else if (confirmType == "{{ Service::BOOKING_DIRECTLY }}") {
                $('.confirm-first-book-desc .desc-content').html("{{ trans('main.Pay online or onsite if onsite booking is with confirmation') }}");
            }
        } else {
            $('.confirm-first-book-desc .desc-content').html("");
        }
    }

    function onChangeConfirmOption(event) {
        changePaymentTypeDescription();
    }

    function onNextOfficeStep() {
        $('.is-invalid').removeClass('is-invalid');
        var currentOfficeForm = '.office-detail-form-' + selectedOffices[step2OfficeCompleted];
        if (!checkRequiredFields([currentOfficeForm + ' .onsite_type'])) {
            return;
        }
        
        var onSiteType = $(currentOfficeForm + " .onsite_type input[type=radio]:checked").val();
        if (onSiteType != ONSITE_TYPE.ONSITE) {
            if(!checkRequiredFields([currentOfficeForm + ' .provide-range-input'])) {
                return;
            }
        }

        step2OfficeCompleted++;
        if (step2OfficeCompleted >= selectedOffices.length) {
            navigateStep(3, true);
        } else {
            showOrHideProvideRange(step2OfficeCompleted);
            $('.step2-offices-container .office-detail-form').hide();
            $('.step2-offices-container .office-detail-form-' + selectedOffices[step2OfficeCompleted]).show();
            window.scrollTo(0, 0);
        }
    }

    function onPreviousOfficeStep() {
        if (step2OfficeCompleted > 0) {
            step2OfficeCompleted--;
            showOrHideProvideRange(step2OfficeCompleted);
            $('.step2-offices-container .office-detail-form').hide();
            $('.step2-offices-container .office-detail-form-' + selectedOffices[step2OfficeCompleted]).show();
            window.scrollTo(0, 0);
        } else {
            hideStep2OfficeForm();
        }
    }

    function showStep2OfficeForm(step2Office) {
        step2OfficeCompleted = step2Office;
        $('.main-step-navigation-container').hide();
        $('.step2-navigation-container').show();
        $('.step2-options-container').hide();
        $('.step2-offline-options-container').hide();
        $('.step2-offices-container').show();
        $('.step2-offices-container .office-detail-form').hide();
        $('.step2-offices-container .office-detail-form-' + selectedOffices[step2OfficeCompleted]).show();
        showOrHideProvideRange(step2OfficeCompleted);
    }

    function hideStep2OfficeForm() {
        $('.main-step-navigation-container').show();
        $('.step2-navigation-container').hide();
        $('.step2-options-container').show();
        $('.step2-offline-options-container').show();
        $('.step2-offices-container').hide();
        $('.step2-offices-container .office-detail-form').hide();
    }

    function showOrHideProvideRange(step2OfficeCompleted) {
        var nextOfficeForm = '.office-detail-form-' + selectedOffices[step2OfficeCompleted];
        onSiteType = $(nextOfficeForm + " .onsite_type input[type=radio]:checked").val();
        changeProvideOnsiteType(onSiteType);
    }

    function onSelectCategory(event) {
        $('#sel-sub-category-id').empty();
        $('#sel-sub-category-id').append("<option value=''>{{ trans('main.Select a specific sector') }}</option>");

        const selectedCatId = event.target.value;
        const subCats = _.get(subCategories, selectedCatId, null);

        if (subCats) {
            for (let subCat of subCats) {
                $('#sel-sub-category-id').append(`<option value="${subCat['id']}">`
                    + subCat['name'] + '</option>');
            }
        }

        changeDefaultPhoto();
    }

    function changeDefaultPhoto() {
        if (!photo) {
            var photoName = $('#sel-category-id option:selected').attr('data-photo');
            $('#service-photo').attr('src', '/img/app_category/' + photoName);
        }
    }

    function onActivateOffice(e) {
        var officeContainer = $(e.target).closest('.office-select-container');
        var officeId = e.target.value;

        if (e.target.checked) {
            selectedOffices.push(officeId);
        } else {
            selectedOffices = selectedOffices.filter(function(value, index, arr){ 
                return value != officeId;
            });
        }
    }

    function onChangeExtraCostType(e) {
        var extraCostType = e.target.value;
        changeExtraCostType(extraCostType);
    }

    function changeExtraCostType(extraCostType) {
        if (extraCostType == extra_price_type.EXTRA_FIX
            || extraCostType == extra_price_type.EXTRA_KILOMETER)
        {
            $('.extra-price-container').show();
        } else {
            $('.extra-price-container').hide();
        }
    }

    function setStep3VisibleOptions() {
        setVisibleDiscountMultiple();
        setVisibleExtraPriceOption();
    }

    function setVisibleDiscountMultiple() {
        var provideOnlineType = $('.provide_online_type input[type=radio]:checked').val();
        if (provideOnlineType == PROVIDE_ONLINE_TYPE.ONLINE) {
            var bookCount = $('select[name=online_book_count]').val();
            if (bookCount > 1) {
                $('.online-extra-discount-container').show();
            } else {
                $('.online-extra-discount-container').hide();
            }
        } else {
            var isMultipleElement = $('input[name=is_multiple_service]');
            var paymentOnline = isPaymentOnline();
            if (isMultipleService() && paymentOnline) {
                $('.offline-extra-discount-container').show();
                isMultipleElement.val(1);
            } else {
                $('.offline-extra-discount-container').hide();
                isMultipleElement.val(0);
            }
        }        
    }

    function isPaymentOnline() {
        var paymentType = $('.client_payment_type input:checked').val();

        if (paymentType == CLIENT_PAYMENT_TYPE.ONLINE
            || paymentType == CLIENT_PAYMENT_TYPE.ONLINE_OR_ONSITE)
        {
            return true;
        }

        return false;
    }

    function isMultipleService() {
        var provideOnlineType = $('.provide_online_type input[type=radio]:checked').val();
        if (provideOnlineType == PROVIDE_ONLINE_TYPE.ONLINE) {
            var bookCount = $('select[name=online_book_count]').val();
            if (bookCount > 1) {
                return true;
            } else {
                return false;
            }
        } else {
            var bookCount, bookConsecutively;
            for (let officeId of selectedOffices) {
                bookCount = $('select[name="office_info[' + officeId + '][book_count]"]').val();
                bookConsecutively = $('select[name="office_info[' + officeId + '][book_consecutively]"]').val();

                if (bookCount > 1 || bookConsecutively > 1) {
                    return true;
                }
            }

            return false;
        }
    }

    function setVisibleExtraPriceOption() {
        var isOffsiteElement = $('input[name=is_available_offsite]');
        if (isAvailableOffsite()) {
            $('.extra-price-option-container').show();
            isOffsiteElement.val(1);
        } else {
            $('.extra-price-option-container').hide();
            isOffsiteElement.val(0);
        }
    }

    function isAvailableOffsite() {
        for (let officeId of selectedOffices) {
            isOffsite = $('input[name="office_info[' + officeId + '][onsite_type]"]:checked').val();
            if (isOffsite != ONSITE_TYPE.ONSITE) {
                return true;
            }
        }

        return false;
    }

    function onChangeImage() {
        $('#photo-input').trigger('click');
    }

    function initPhotoInput() {
        $('#photo-input').on('change', function (event) {
            const url = "/service/upload/photo";

            data = new FormData();
            data.append('photo', $('#photo-input')[0].files[0]);

            ajax_post(url, data, function(res) {
                $('#photo-file-name').val(res.data);
                $('#service-photo').attr('src', "{{ HTTP_SERVICE_PATH }}" + res.data);
            }, 'multipart/form-data');
        });
    }
</script>
