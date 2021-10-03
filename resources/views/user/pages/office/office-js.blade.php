@php
    use App\Models\CompanyType;
@endphp

<script>
var officeRegionId = "{{ old('region_id', $office->region_id ?? '') }}";
var officeCityId = "{{ old('city_id', $office->city_id ?? '') }}";
var holidays = "{{ old('holidays', $office->holidays ?? '') }}";
var currentStep = 1;
var lastNavigatedStep = currentStep - 1;
var MAX_NAVIGATE_STEP = 5;

$(function () {
    initWeekTimes();
    initMDP();
    $('.has_calendar input[type=radio]').on('change', onEnableCalendar);

    setTimeout(() => {
        initMap();
    }, 100);

    initVisibleContainers();
    filterUniqCode();
    // testNavigate();
});

function filterUniqCode() {
    $('input[name=invoice_unique_code]').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    });
}

function initVisibleContainers() {
    var isWizardMode = $('input[name=save_next]').lenth > 0;
    if (isWizardMode) {
        var hasCalendar = $('.has_calendar input[type=radio]:checked').val();
        if (hasCalendar == 1) {
            $('.calendar-container').show();
        } else {
            $('.calendar-container').hide();
        }
    }

    var countryId = $('select[name=country_id]').val();
    if (countryId) {
        loadRegion(countryId, function() {
            if (officeRegionId) {
                setTimeout(() => {    
                    $('select[name=region_id]').val(officeRegionId);
                    $('select[name=region_id]').selectpicker('refresh');
                    loadCities(officeRegionId, function() {
                        if (officeCityId) {
                            setTimeout(() => {
                                $('select[name=city_id]').val(officeCityId);
                                $('select[name=city_id]').selectpicker('refresh');
                            }, 500);
                        }
                    });
                }, 500);
            }
        });
    }

    var address = $('input[name=address]').val();
    if (address) {
        findAddress();
    }
}

function testNavigate() {
    navigateStep(currentStep);
}

function onNextOfficeStep() {
    if (currentStep < MAX_NAVIGATE_STEP) {
        navigateStep(currentStep + 1)
    }
}

function onPreviousOfficeStep() {
    if (currentStep >= 2) {
        navigateStep(currentStep - 1);
    }
}

function navigateStep(targetStep) {
    if (targetStep > currentStep && !validateForm()) {
        return false;
    }

    if (lastNavigatedStep + 2 == targetStep) {
        lastNavigatedStep = currentStep;
    }

    //initialize steps
    $('.bs-vertical-wizard ul li').removeClass();
    $('.bs-vertical-wizard ul li .fa-check').addClass('d-none');

    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('current-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').hide();

    // set step marks
    $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ')').removeClass().addClass('current');
    $('.bs-vertical-wizard ul li:nth-child(' + (targetStep) + ') .fa-check').addClass('d-none');
    $('.bs-vertical-wizard ul li:lt(' + lastNavigatedStep + ')').addClass('complete');
    $('.bs-vertical-wizard ul li:lt(' + lastNavigatedStep + ') .fa-check').removeClass('d-none');

    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').eq(targetStep - 1).addClass('current-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').removeClass('completed-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').children('li').slice(0, targetStep - 1).addClass('completed-step');
    $('.bs-horizontal-wizard ul.multistep-progressbar').find('li .step-icon').slice(0, targetStep - 1).show();

    if (targetStep == 1) {
        $('#previous-to-profile').show();
        $('#previous-office-step').hide();
    } else {
        $('#previous-to-profile').hide();
        $('#previous-office-step').show();
    }

    if (targetStep == 5) {
        $('#next-office-step').hide();
        $('#complete-office-step').show();
        initStep5Address();
    } else {
        $('#next-office-step').show();
        $('#complete-office-step').hide();
    }

    $("#office-form-step" + currentStep).fadeOut(function() {
        $("#office-form-step" + targetStep).fadeIn();
    });
    window.scrollTo(0, 0);

    if (targetStep == 2) {
        map.setZoom(8);
    }

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
    var isValid = true;
    var inputsRequired = ['input[name=name]', 'input[name=phone_number]'];
    
    isValid = checkRequiredFields(inputsRequired);
    return isValid;
}

function validateStep2() {
    var isValid = true;
    var inputsRequired = [
        'select[name=country_id]',
        'select[name=region_id]',
        'select[name=city_id]',
        'input[name=address]',
        'input[name=zip_code]'
    ];
    
    isValid = checkRequiredFields(inputsRequired);
    return isValid;
}

function validateStep3() {
    var isValid = true;
    
    return isValid;
}

function validateStep4() {
    var isValid = true;
    var inputsRequired = ['.has_calendar'];
    
    isValid = checkRequiredFields(inputsRequired);
    return isValid;
}

var ITALY_ID = 109;
function validateStep5() {
    var inputsRequired = [
        'input[name=company_name]',
        'select[name=invoice_country_id]',
        'select[name=company_type_id]',
        'input[name=invoice_vat_id]',
        'input[name=invoice_city]',
        'input[name=billing_zip_code]',
        'input[name=billing_addr]'
    ];

    var isValid = checkRequiredFields(inputsRequired);

    var countryId = $('#invoice-country').val();
    var companyType = $('#company-type-id').val();

    if (countryId == ITALY_ID && companyType == "{{ CompanyType::TAX_VAT }}") {
        var uniqueCodeElement = $('input[name=invoice_unique_code]');
        var pecElement = $('input[name=invoice_pec]');
        if (!uniqueCodeElement.val() && !pecElement.val()) {
            setError(uniqueCodeElement, "{{ trans('main.Enter Unique code or PEC') }}");
            setError(pecElement, "{{ trans('main.Enter Unique code or PEC') }}");

            isValid = false;
        }
    }
    
    return isValid;
}

function initStep5Address() {
    var countryId = $('#invoice-country').val();
    if (!countryId) {
        var officeCountryId = $('select[name=country_id]').val();
        $('#invoice-country').val(officeCountryId);
        selectCountry(officeCountryId);
    }

    var city = $('input[name=invoice_city]').val();
    if (!city) {
        var officeCity = $('select[name="city_id"] option:selected').html();
        $('input[name=invoice_city]').val(officeCity);
    }

    var address = $('input[name=billing_addr]').val();
    if (!address) {
        var officeAddr = $('input[name=address]').val();
        $('input[name=billing_addr]').val(officeAddr);
    }

    var zipCode = $('input[name=billing_zip_code]').val();
    if (!zipCode) {
        var officeZipcod = $('input[name=zip_code]').val();
        $('input[name=billing_zip_code]').val(officeZipcod);
    }
}

function onEnableCalendar(e) {
    var isEnable = e.target.value;
    if (isEnable == 1) {
        $('.calendar-container').show();
    } else {
        $('.calendar-container').hide();
    }
}

function initWeekTimes() {
    $('.timetables_container').each(function () {
        disableClosingTimes($(this).find('select')[0]);
    });

    $('[name$=_start]').on('change', function () {
        var $start = $(this);
        var $end = $start.closest('.row').find('[name$=_end]');

        if ($start.val() === "closed") {
            $end.val('closed');
        }
    });
}

function disableClosingTimes(select) {
    var closeSelect = $($(select).closest('.timetables_container').find('select')[1]);
    if ($(select).val() !== 'closed') {
        var openTime = moment($(select).val(), 'H:mm');

        closeSelect.find('option').each(function (index, option) {
            if ($(option).val() !== 'closed') {

                var closeTime = moment($(option).val(), 'H:mm');
                if (closeTime <= openTime) {
                    $(option).attr('disabled', 'disabled');
                } else {
                    $(option).removeAttr('disabled');
                }

            } else {
                $(option).attr('disabled', 'disabled');
            }

        });

        if (openTime.format('H:mm').indexOf("21:30") > -1) {
            closeSelect.find('option').removeAttr('disabled');
            closeSelect.val("22:30");
        }

        if (closeSelect.val() === 'closed' || closeSelect.val() == null) {
            closeSelect.val(openTime.add(1, 'h').format('HH:mm'));
        }
    } else {
        $($(select).closest('.timetables_container').find('select')[1]).
            find('option').
            each(function (index, option) {

                if ($(option).val() !== 'closed') {
                    $(option).attr('disabled', 'disabled');
                } else
                    $(option).removeAttr('disabled');
            });
        $($(select).closest('.timetables_container').find('select')[1]).val('closed');
    }
}

// initialize multiple datepicker
function initMDP() {
    var today = new Date();
    var y = today.getFullYear();
    var newYear = '1/1/' + y;

    //multiDatePicker
    if (holidays == '') {
        holidays = newYear;
    }

    holidays = holidays.replace(/\s/g, '');
    holidays = holidays.split(',');

    // For Italiano
    $.datepicker.regional['it'] = {
        closeText: 'Chiudi', // set a close button text
        currentText: 'Oggi', // set today text
        monthNames: [
            'Gennaio',
            'Febbraio',
            'Marzo',
            'Aprile',
            'Maggio',
            'Giugno',
            'Luglio',
            'Agosto',
            'Settembre',
            'Ottobre',
            'Novembre',
            'Dicembre'], // set month names
        monthNamesShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'], // set short month names
        dayNames: [
            'Domenica',
            'Luned&#236',
            'Marted&#236',
            'Mercoled&#236',
            'Gioved&#236',
            'Venerd&#236',
            'Sabato'], // set days names
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'], // set short day names
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Me', 'Gio', 'Ve', 'Sa'], // set more short days names
    };

    // For Espanol
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    };

    $.datepicker.setDefaults($.datepicker.regional['{{ App::getLocale() ? App::getLocale() : "en" }}']);
    $('#full-year').multiDatesPicker({
        // $.datepicker.regional[ "hi" ]
        refresh: 'refresh',
        dateFormat: 'mm/dd/yy',
        addDates: holidays,
        numberOfMonths: [3, 4],
        defaultDate: '1/1/' + y,
        altField: '#holidays',
    });
}

function onChangeCountry(e) {
    var countryId = e.target.value;
    loadRegion(countryId);
    findAddress();
}

function loadRegion(countryId, callback=null) {
    $('select[name=region_id]').empty();
    $('select[name=city_id]').empty();
    ajax_get(
        "{{ route('user.region.all') }}" + '?country_id=' + countryId,
        function(err, result) {
            $('select[name=region_id]').append('<option value="">' + "{{ trans('main.Enter office region') }}" + '</option>');
            for (let region of result.data) {
                $('select[name=region_id]').append('<option value="' + region.id + '">' + region.name + '</option>');
            }

            $('select[name=region_id]').selectpicker('refresh');
            $('select[name=region_id]').val('');
            $('select[name=region_id]').selectpicker('refresh');

            if (callback) {
                callback();
            }
        }
    );
}

function onChangeRegion(e) {
    var regionId = e.target.value;
    loadCities(regionId);
    findAddress();
}

function loadCities(regionId, callback=null) {
    $('select[name=city_id]').empty();
    ajax_get(
        "{{ route('user.city.all') }}" + '?region_id=' + regionId,
        function(err, result) {
            $('select[name=city_id]').append('<option value="">' + "{{ trans('main.Enter office city') }}" + '</option>');
            for (let city of result.data) {
                $('select[name=city_id]').append('<option value="' + city.id + '">' + city.name + '</option>');
            }

            $('select[name=city_id]').selectpicker('refresh');
            $('select[name=city_id]').val('');
            $('select[name=city_id]').selectpicker('refresh');

            if (callback) {
                callback();
            }
        }
    );
}

var map;
var marker = null;
var lat, lng, zoom;

// initialize google map
function initMap() {
    lat = $("input[name='lat']").val() || 41.9028;
    lng = $("input[name='lng']").val() || 12.4964;
    lat = parseFloat(lat);
    lng = parseFloat(lng);

    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: lat, lng: lng }
    });

    $('select[name="country_id"]').on('change', onChangeCountry);
    $('select[name="region_id"]').on('change', onChangeRegion);
    $('select[name="city_id"]').on('change', function() { findAddress() });

    $('input[name="address"]').on('keyup', function () {
            findAddress();
        }
    );

    findAddress();
}

function findAddress() {
    var address = $('select[name="country_id"] option:selected').text() + ' '
        + ($('select[name="region_id"] option:selected').html() || '') + ' '
        + ($('select[name="city_id"] option:selected').html() || '') + ' '
        + $('input[name="address"]').val();

    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();

            $("input[name='lat']").val(latitude);
            $("input[name='lng']").val(longitude);
            if (marker !== null) {
                marker.setMap(null);
            }

            map.setCenter(results[0].geometry.location);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            if (results[0].geometry.viewport) {
                map.fitBounds(results[0].geometry.viewport);
            }
        }
    });
}

function formSubmit(e) {
    e.preventDefault();

    if (is_office_wizard && !validateStep5()) {
        return;
    }

    var address = jQuery('select[name="country_id"] option:selected').html() + ' '
        + $('select[name="region_id"] option:selected').html() + ' '
        + $('select[name="city_id"] option:selected').html() + ' '
        + $('input[name="address"]').val();

    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({ 'address': address }, function (results, status) {

        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();

            $("input[name='lat']").val(latitude);
            $("input[name='lng']").val(longitude);
            
            $('#form-office').trigger('submit');
        }
        else {
            alert('main.Please input your address correctly!');
        }
    });

    return false;
}
</script>