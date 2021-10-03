@php
use App\Models\ServiceOffice;
use App\Models\Service;
@endphp

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API') }}&libraries=places"></script>

<script>
    var locale = "{{ App::getLocale() ?: 'en' }}";

    var seller_id = {!! $owner->id !!};
    var user_id = {!! auth()->id() ?? 'null' !!};

    var service_id = {!! $service->id !!};
    var service_price = {!! $service->price !!};
    var is_online = !!"{{ $is_online }}";
    var is_offline = !is_online;
    var client_payment_type = "{{ $service->client_payment_type }}";
    var discount_single = {!! $service->discount_percentage_single ?: 0 !!}
    var discount_multiple = {!! $service->discount_percentage_multiple ?: 0 !!}
    var provide_online_type = "{{ $service->provide_online_type }}";
    var delivery_time = "{{ $service->online_delivery_time }}";
    var service_duration = "{{ $service->duration }}";
    var online_file_required = "{{ $service->online_file_required }}";
    var booking_confirm = "{{ $service->booking_confirm }}"

    var offices = @json($offices);
    var office_count = {!! $offices_count !!};
    var office_end_time;

    var booking_date,
        booking_date_YMD,
        booking_date_time;

    var selected_office;
    var selected_dates = [];
    var total_book_count = 0;
    var book_count_by_time = 0;
    var total_duration = service_duration;

    var current_step = 1; // booking wizard step
    var booking_step_count = $('#booking-form-container').children('div.step-form')
    .length; // total step count of wizard
    var time_step_count = 1;
    var current_time_step = 1;

    $(function() {
        @if ($errors->has('email') || $errors->has('password'))
            $('#login-modal').modal();
        @endif

        if (office_count == 1) {
            selected_office = offices[0];
            $('.step-wizard-select-date').addClass('current-step');
            $('.step-select-date').show();
            $('.booking-section.booking-section__calendar').show();
        }

        initMap();
        initDatepicker();
        initPayButtonListener();

        $('.booking-office__item').on('click', onSelectOffice);
        $('body').on('click', '.available-hours a', onSelectTime);
        $('body').on('click', '.more-hours a', onSelectMoreTime);
        $(document).on('click', '.more-service-count', onSelectMoreService);
        $("#btn-pay-offline").on('click', onPayOffline);
        $("#btn-pay-online").on('click', onPayOnline);
        $("#btn-pay-office").on('click', onPayInOffice);
        $("#btn-pay-free").on('click', onPayFree);
        $('#user_addr_input').on('keyup', onChangeAddr)
        $('#online_file').on('change', onChangeOnlineFile);
        $('.btn-booking-next').on('click', onNextStep);
        $('.btn-booking-previous').on('click', onPreviousStep);
    });

    function onNextStep() {
        ajax_post('{{ url('/') }}/booking/check-auth', {}, function(result) {
            if (current_step >= booking_step_count) {
                return;
            }

            if (!validateStep()) {
                return;
            }

            if (is_offline) {
                var stepForm = $('li.current-step').first().attr('data-step-form');
                if (stepForm == 'time' && time_step_count > 1) {
                    if (nextTimeStepWizard()) {
                        return;
                    }
                }
            }

            updateStepWizard(current_step - 1, current_step);
            current_step++;

            showNavigationButton();
        }, 'application/json', function(err) {
            if (err.response.status == 401) {
                $('#login-modal').modal();
            }
        });
    }

    function onPreviousStep() {
        if (current_step <= 1) {
            return;
        }

        var stepForm = $('li.current-step').first().attr('data-step-form');
        if (stepForm == 'time' && time_step_count > 1) {
            if (previousTimeStepWizard()) {
                return;
            }
        }

        updateStepWizard(current_step - 1, current_step - 2);
        current_step--;

        showNavigationButton();
    }

    function showNavigationButton() {
        if (current_step == booking_step_count) {
            $('.btn-booking-next').hide();
        } else {
            $('.btn-booking-next').show();
        }

        if (current_step == 1) {
            $('.btn-booking-previous').hide();
        } else {
            $('.btn-booking-previous').show();
        }
    }

    function validateStep() {
        $('.js-validation-error').addClass('d-none').hide();
        $('.is-invalid').removeClass('is-invalid');

        var stepForm = $('li.current-step').first().attr('data-step-form');
        var isValid = false;

        $('.validation-error').hide();

        switch (stepForm) {
            case 'office':
                return validateStepOffice();
                break;
            case 'date':
                return validateStepDate();
                break;
            case 'time':
                return validateStepTime();
                break;
            case 'upload':
                return validateStepUpload();
                break;
            case 'payment':
                return true;
            default:
                break;
        }

        return isValid;
    }

    function validateStepOffice() {
        var isSelectedOffice = $('.booking-office__item.is-selected').length > 0;
        if (isSelectedOffice) {
            return true;
        }

        $('.error-office-required').show();
        return false;
    }

    function validateStepDate() {
        if (booking_date) {
            return true;
        }

        $('.error-date-required').show();
        return false;
    }

    function validateStepTime() {
        if (is_online) {
            return true;
        }

        if (selected_dates.length > 0) {
            return true;
        }

        $('.error-time-required').show();
        return false;
    }

    function validateStepUpload() {
        if (is_online) {
            var inputsRequired = [
                'textarea[name=user_msg]'
            ];

            if (online_file_required == 1) {
                inputsRequired.push('input[name=online_file]');
            }

            if (!checkRequiredFields(inputsRequired)) {
                return false;
            }
        } else {
            if (selected_office.onsite_type == "{{ ServiceOffice::TYPE_OFFSITE }}") {
                var inputsRequired = [
                    '#user_addr_input'
                ];

                if (!checkRequiredFields(inputsRequired)) {
                    return false;
                }
            }
        }

        return true;
    }

    function updateStepWizard(currentStep, newStep) {
        $('#booking-form-container').children('div.step-form').eq(currentStep).hide();
        $('#booking-form-container').children('div.step-form').eq(newStep).show();

        $('ul.multistep-progressbar').children('li').removeClass('current-step');
        $('ul.multistep-progressbar').children('li').eq(newStep).addClass('current-step');

        $('ul.multistep-progressbar').children('li').removeClass('completed-step');
        $('ul.multistep-progressbar').children('li').slice(0, newStep).addClass('completed-step');

        $('ul.multistep-progressbar').find('li .step-icon').hide();
        $('ul.multistep-progressbar').find('li .step-icon').slice(0, newStep).show();

        var bookingWizard = document.getElementById('booking-step-wizard');
        window.scrollTo(0, bookingWizard.offsetTop - 100);
    }

    function nextTimeStepWizard() {
        if (current_time_step >= time_step_count) {
            return false;
        }

        if (current_time_step == 1) {
            $('.booking-section__hours').hide();

            if (selected_office.book_consecutively > 1 && $('.more-hours .btn-hour').length > 0) {
                $('.booking-section__more-hours').show();
            } else if ($('.duration-selector').attr('data-count') > 1) {
                $('.booking-section__more-service').show();
                current_time_step++;
            } else {
                current_time_step += 2;
            }
        } else if (current_time_step == 2) {
            $('.booking-section__more-hours').hide();
            $('.booking-section__more-service').show();
        }

        current_time_step++;
        return true;
    }

    function previousTimeStepWizard() {
        if (current_time_step == 1) {
            return false;
        }

        if (current_time_step == 3) {
            $('.booking-section__more-hours').show();
            $('.booking-section__more-service').hide();
        } else if (current_time_step == 2) {
            $('.booking-section__more-hours').hide();
            $('.booking-section__more-service').hide();
            $('.booking-section__hours').show();
        }

        current_time_step--;
        return true;
    }

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 12,
            center: {
                lat: parseFloat(offices[0]['lat']),
                lng: parseFloat(offices[0]['lng'])
            },
            mapTypeId: 'terrain'
        });

        var bounds = new google.maps.LatLngBounds();
        var center;

        for (let office of offices) {
            center = {
                lat: parseFloat(office['lat']),
                lng: parseFloat(office['lng'])
            };

            var cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: center,
                radius: 200,
            });
        }
        // var infowindow = new google.maps.InfoWindow;
        var marker, i;

        setTimeout(function() {
            var markers = [];
            for (let office of offices) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(parseFloat(office['lat']), parseFloat(office[
                        'lng'])),
                    map: map,
                });
                bounds.extend(marker.getPosition());
                markers.push(marker);
            }

            if (markers.length > 1) {
                map.fitBounds(bounds);
            }

            google.maps.event.addListener(map, 'zoom_changed', function() {
                var zoom = map.getZoom();
                // iterate over markers and call setVisible
                for (i = 0; i < markers.length; i++) {
                    markers[i].setVisible(zoom <= 14);
                }
            });
        }, 1000);
    }

    function initDatepicker() {
        for (let office of offices) {
            var disabledDates = [];
            if (office['booked_days'].length > 0) {
                disabledDates = disabledDates.concat(office['booked_days']);
            }

            if (office['reserved_days'].length > 0) {
                disabledDates = disabledDates.concat(office['reserved_days']);
            }

            if (office['holidays']) {
                var holidays = office['holidays'].split(',');
                disabledDates = disabledDates.concat(holidays);
            }

            if (is_offline && !(client_payment_type == "{{ Service::PAYMENT_TYPE_ONLINE }}" &&
                    booking_confirm == "{{ Service::BOOKING_DIRECTLY }}")) {
                var today = new Date();
                var tomorrow = new Date(today);

                for (var i = 1; i < 5; i++) {
                    tomorrow.setDate(today.getDate() + i);
                    disabledDates = disabledDates.concat([dateFormat(today), dateFormat(tomorrow)]);
                }
            }

            $('.date-picker-' + office['office_id']).datepicker({
                language: locale,
                beforeShowDay: function(date) {
                    dmy = dateFormat(date, 'd/m/y');

                    if (disabledDates.indexOf(dmy) != -1) {
                        return false;
                    } else {
                        return true;
                    }
                },
                daysOfWeekDisabled: office['week_holidays'],
                format: 'dd/mm/yyyy',
                startDate: new Date(),
            }).on('changeDate', function(e) {
                if (is_offline) {
                    onSelectBookingDate(e);
                } else {
                    onSelectStartDate(e);
                }
            }).on('hide', function(e) {
                if (is_offline) {
                    onSelectBookingDate(e);
                } else {
                    onSelectStartDate(e);
                }
            });
        }
    }

    function onSelectOffice(event) {
        $('.booking-office__item').removeClass('is-selected');
        $(this).addClass("is-selected");

        var officeId = $(this).data('office-id');
        selected_office = offices.find(item => item.office_id == officeId);

        $('.date-picker').hide();
        $('.date-picker-' + $(this).data('office-id')).show();
        $(".booking-section__calendar").show();
        booking_date = '';
        booking_date_time = '';
        // $('.date-picker').datepicker('setDate', null);

        $('.booking-section__more-service').hide();
        $('.booking-section__more-hours').hide();
        $('.booking-section__hours').hide();
        $('.booking-section__offsite').hide();
        $('.booking-section__message').hide();

        $('input[name="user_addr"]').val('');

        // $(".booking-section__details-container").css('visibility', 'visible');
        setNumberOfServiceInDetail(1);

        $(".detail-place").find(".value").html(selected_office.city);
    }

    function onSelectBookingDate(e) {
        if (e.date) {
            book_count_by_time = 0;
            setNumberOfServiceInDetail(book_count_by_time);
            booking_date = e.format('dd/mm/yyyy');
            booking_date_YMD = e.format('yyyy-mm-dd');
            $(".detail-date").find('.value').html(booking_date);

            generateHoursSelector(booking_date);
            if (booking_date) {
                // Showed times and more times section
                $('.booking-section__hours').show(); // show original hours 
                // $('.booking-section__details-container').css('visibility', 'visible'); // show details box 
            }
        }
    }

    function generateHoursSelector(date) {
        selected_dates = [];
        $('.available-hours a').each(function(index) {
            $(this).removeClass('active');
        });

        showLoadingProgress();
        $.get('{{ route('user.service.availableHours') }}', {
            date: date,
            service_id: service_id,
            office_id: selected_office.office_id,
        }).done(function(response) {
            loadServiceTimes(date, response.available_hours, response.office_end_time);
        }).always(function() {
            hideLoadingProgress();
        });
    }

    function loadServiceTimes(date, availHours, officeEndTime) {
        var hours = '';
        var currentTime = new Date().toTimeString().split(" ")[0];
        var currentDate = moment(new Date()).format("dd/mm/yy");
        if (availHours.length === 0) {
            hours = "<b>No available time</b>";
            $('.duration-selector').hide();
            $('.time-text-tab').html('');
        } else {
            office_end_time = officeEndTime;
            for (var i = 0; i < availHours.length; i++) {
                if (!(date == currentDate) || Date.parse('2017-01-01 ' + availHours[i].hour) > Date.parse(
                        '2017-01-01 ' + currentTime)) {
                    var isDisable = '';
                    if (availHours[i].bookingAvailable == 0) {
                        isDisable = 'disabled';
                    }

                    hours += '<a href="#" id="items' + i + '" class="btn btn-hour ' + isDisable + '"' +
                        '"  data-id="' + i + '" data-hour="' + availHours[i].hour + '"' +
                        ' data-booking-available-count="' + availHours[i].bookingAvailable + '">' +
                        availHours[i].hourLabel + '</a>';
                }
            }
            $('.time-text-tab').html('');
        }

        // also cloned for more hours 
        $('.detail-duration .value').html('');
        $('.available-hours').html(hours);
        $('.more-hours').html('');
    }

    function onSelectTime(event) {
        event.preventDefault();

        total_duration = service_duration;

        $('.available-hours a').removeClass('active');
        $(this).addClass('active');

        var curTime = $(this).data('hour');
        if (selected_office.book_consecutively > 1) {
            var hours = '';
            var officeEndDate = Date.parse('2017-01-01 ' + office_end_time);

            for (var i = 2; i <= selected_office.book_consecutively; i++) {
                var serviceEndTime = addMinutes(curTime, total_duration * i);
                var serviceEndDate = moment(Date.parse('2017-01-01 ' + curTime)).add(total_duration * i, 'm').toDate();

                if (serviceEndDate > officeEndDate) {
                    break;
                }

                hours += '<a href="#" data-hour="' + curTime + '" data-book-count="' + i +
                    '" class="btn btn-hour">' + curTime + ' - ' + serviceEndTime + '</a>';
            }
            $('.more-hours').html(hours);
        }

        book_count_by_time = 1;
        total_book_count = 1;
        selected_dates = [booking_date_YMD + " " + curTime];

        setBookingTimeDetail($(this).text());
    }

    function setBookingTimeDetail(timePeriod) {
        // display number of booking in detail area
        setNumberOfServiceInDetail(book_count_by_time);

        // display booking time in detail area
        var hourPeriod = timePeriod.split('-');

        first_time = hourPeriod[0].replace(/\s+/g, '');
        last_time = hourPeriod[1].replace(/\s+/g, '');

        time_text = first_time + ' - ' + last_time;

        $('.time-text-tab').html(time_text);

        booking_date_time = booking_date + ' ' + first_time;

        if (first_time) {
            // booking at a same time
            generateBookDurationSelector();
        } else {
            $('#duration-select').hide();
        }
    }

    // booking at a same time
    function generateBookDurationSelector() {
        ajax_post('{{ url('/') }}/booking/books-left', {
            service_id: service_id,
            office_id: selected_office.office_id,
            book_date: selected_dates,
        }, function(result) {
            var count = _.get(result.data, 'books_left', 0);

            generateMoreServiceTimeSlot(count);

            if (selected_office.onsite_type == "{{ ServiceOffice::TYPE_ONSITE }}") {
                $(".booking-section__offsite").hide();
                $('#user_addr_input').hide();
                $('#user_addr_input').removeAttr('required');
            } else {
                $(".booking-section__offsite").show();
                $('#user_addr_input').show();
                $('#user_addr_input').attr('required');
            }

            $(".booking-section__message").show();

            calculateOfflinePrice();
        });
    }

    function onSelectMoreTime(e) {
        e.preventDefault();

        $('.more-hours a').removeClass('active');
        $(this).addClass('active');

        $('.available-hours a').removeClass('active');

        var curTime = $(this).data('hour');
        book_count_by_time = $(this).data('book-count');

        selected_dates = [];
        for (var i = 0; i < book_count_by_time; i++) {
            var startTime = addMinutes(curTime, total_duration * i);
            selected_dates.push(booking_date_YMD + " " + startTime)
        }

        setBookingTimeDetail($(this).text());
    }

    function onSelectMoreService(e) {
        $('.more-service-count').removeClass('active');
        $(this).addClass('active');

        if (is_online) {
            calculateOnlinePrice();
        } else {
            calculateOfflinePrice();
        }
    }

    function onChangeAddr(e) {
        var newAddr = e.target.value;
        if (newAddr) {
            $('.detail-extra-price').show();
        } else {
            $('.detail-extra-price').hide();
        }

        newAddr = newAddr || selected_office.city;
        $('.booking-section__detail .detail-place .value').html(newAddr);
    }

    function calculateOfflinePrice() {
        /* Code from modal */
        var bookMoreService = getNumberBookMoreService();

        total_book_count = book_count_by_time * bookMoreService;
        setNumberOfServiceInDetail(total_book_count);

        if ($('.duration-selector').length > 0) {
            total_duration = total_book_count * service_duration;
        } else {
            total_duration = service_duration;
        }
        $('.detail-duration .value').html(getPrettyDuration(total_duration));

        updatePaymentForm();
    }

    function updatePaymentForm() {
        if (is_offline && client_payment_type == "{{ Service::PAYMENT_TYPE_FREE }}" ||
            service_price == 0) {
            $('.payment-subtotal').hide();
            $('.payment-discount').hide();
            return;
        }

        // update payment form
        var subTotal = service_price * total_book_count;
        var discount = getServiceDiscount(total_book_count) * total_book_count;
        var total = subTotal - discount;

        // setting price info to labels
        if (is_offline && client_payment_type == "{{ Service::PAYMENT_TYPE_ONLINEONSITE }}") {
            if (discount == 0) {
                $('.payment-discount').hide();
            } else {
                $('.payment-discount .value').html('€ ' + discount);
                $('.payment-discount').show();
            }

            $('.payment-subtotal .value').html('€ ' + subTotal);
            $('.payment-total .value').html('€ ' + total);
        } else {
            if (discount == 0) {
                $('.payment-subtotal').hide();
                $('.payment-discount').hide();
                $('.payment-total .value').html('€ ' + total);
            } else {
                $('.payment-subtotal .value').html('€ ' + subTotal);
                $('.payment-discount .value').html('€ ' + discount);
                $('.payment-total .value').html('€ ' + total);

                $('.payment-subtotal').show();
                $('.payment-discount').show();
            }
        }

        // setting price on the payment button
        if (is_online) {
            $('#btn-pay-online .btn-price').html('€&nbsp;' + total);
        } else {
            if (client_payment_type == "{{ Service::PAYMENT_TYPE_ONLINE }}") {
                $('#btn-pay-offline .btn-price').html('€&nbsp;' + total);
            } else if (client_payment_type == "{{ Service::PAYMENT_TYPE_ONSITE }}") {
                $('#btn-pay-office .btn-price').html('€&nbsp;' + total);
            } else if (client_payment_type == "{{ Service::PAYMENT_TYPE_ONLINEONSITE }}") {
                $('#btn-pay-offline .btn-price').html('€&nbsp;' + total);
                $('#btn-pay-office .btn-price').html('€&nbsp;' + subTotal);
            }
        }
    }

    function initPayButtonListener() {
        /* Listener On booking items - showed buttons if necesseary */
        const targetNode = document.querySelector('.booking-step-content');
        const config = {
            attributes: true,
            childList: true,
            subtree: true
        };

        const callback = function(mutationsList, observer) {
            // Use traditional 'for loops' for IE 11
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    checkRequiredItems();
                }
            }
        };
        const observer = new MutationObserver(callback);

        // Start observing the target node for configured mutations
        observer.observe(targetNode, config);
    }

    /* Dependecies for showed buttons */
    function checkRequiredItems() {
        if (is_offline) {
            if (hasOffice() && hasDate() && hasHours()) {
                $(".paid-buttons").css('visibility', 'visible');
                $(".paid-info").show();
                return true;
            }

            $(".paid-buttons").css('visibility', 'hidden');
            $(".paid-info").hide();
            return false;
        } else {
            return true;
        }
    }

    function hasOffice() {
        if (selected_office) {
            return true;
        }

        return false;
    }

    function hasDate() {
        if (booking_date) {
            return true;
        }

        return false;
    }

    function hasHours() {
        if (selected_dates.length > 0) {
            return true;
        }

        return false;
    }

    function onPayOffline(event) {
        if (!checkTermsAccept()) {
            return;
        }

        if (!checkAppLiveMode()) {
            return;
        }

        var msg = $('textarea[name="user_msg"]').val();
        var address = $('input[name="user_addr"]').val();
        var discountPrice = getServiceDiscount(total_book_count);
        var actualPrice = service_price - discountPrice;
        var totalAmount = actualPrice * total_book_count;

        var data = {
            uid: user_id,
            oid: seller_id,
            sid: service_id,
            book_date: booking_date_time,
            delivery_date: $('.detail-delivery-date .value').html(),
            service_price: service_price,
            actual_price: actualPrice,
            discount_price: discountPrice,
            number_of_booking: total_book_count,
            total_duration: total_duration,
            total_amount: totalAmount,
            address: address,
            msg: msg,
            office_id: selected_office.office_id,
            provide_online_type: provide_online_type
        };

        $('#payment-form').find("input[name='data']").val(JSON.stringify(data));

        $("#payment-form").submit();

        $(".btn-book").attr('disabled', 'disabled');
    }

    function onPayInOffice() {
        if (!checkTermsAccept()) {
            return;
        }

        var address = $('input[name="user_addr"]').val();
        var msg = $('textarea[name="user_msg"]').val();

        var totalAmount = service_price * total_book_count;

        var data = {
            sid: service_id,
            oid: seller_id,
            uid: user_id,
            book_date: booking_date_time,
            delivery_date: $('.detail-delivery-date .value').html(),
            total_duration: total_duration,
            service_price: service_price,
            actual_price: service_price,
            discount_price: 0,
            number_of_booking: total_book_count,
            total_amount: totalAmount,
            address: address,
            msg: msg,
            office_id: selected_office.office_id,
            provide_online_type: provide_online_type,
        };

        $('#office-payment-form').find("input[name='data']").val(JSON.stringify(data));
        $("#office-payment-form").submit();

        $(".btn-book").attr('disabled', 'disabled');
    }


    function onPayFree() {
        if (!checkTermsAccept()) {
            return;
        }

        var msg = $('textarea[name="user_msg"]').val();
        var address = $('input[name="user_addr"]').val();

        var data = {
            sid: service_id,
            oid: seller_id,
            uid: user_id,
            book_date: booking_date_time,
            msg: msg,
            number_of_booking: total_book_count,
            office_id: selected_office.office_id,
            provide_online_type: provide_online_type,
            total_amount: 0,
            discount_price: 0,
            total_duration: total_duration,
            address: address,
        };

        $('#free-payment-form').find("input[name='data']").val(JSON.stringify(data));
        $("#free-payment-form").submit();

        $(".btn-book").attr('disabled', 'disabled');
    }

    function onSelectStartDate(e) {
        generateMoreServiceTimeSlot(selected_office['book_count']);

        // description
        $('.booking-section__message').show();

        // order information
        booking_date = e.format('dd/mm/yyyy');
        booking_date_YMD = e.format('yyyy-mm-dd');
        $(".detail-date").find('.value').html(booking_date);

        var deliveryDate = calculateDeliveryDate(booking_date_YMD);
        $('.detail-delivery-date .value').html(dateFormat(deliveryDate, 'd/m/y'));

        $('.booking-section__details-container').css('visibility', 'visible'); // show details box 
        setNumberOfServiceInDetail(1);

        $(".paid-buttons").css('visibility', 'visible');

        $('.booking-section__file').show();

        calculateOnlinePrice();
    }

    function generateMoreServiceTimeSlot(bookCount) {
        // need more service at the same time
        var countOptions = '';
        for (var i = 1; i <= bookCount; i++) {
            countOptions += '<div class="more-service-count btn" data-value=' + i + '>' + i + '</div>';
        }

        var hidden = '';
        if (bookCount === 1) {
            var hidden = 'display: none';
        }
        countOptions = '<div class="more-durations" id="duration-select" style="' + hidden + '">' +
            '<div data-count="' + bookCount + '" class="duration-selector">' + countOptions + '</div>' +
            '</div>';

        $('.book-duration-container').html(countOptions).show();
        $('.more-durations .more-service-count').first().addClass('active');

        if (is_offline) {
            if (selected_office.book_consecutively > 1 && bookCount > 1) {
                time_step_count = 3;
            } else if (selected_office.book_consecutively > 1 || bookCount > 1) {
                time_step_count = 2;
            } else {
                time_step_count = 1;
            }
        }

        if (is_online) {
            $('.booking-section__more-service').show();
        }
    }

    function calculateDeliveryDate(orderDate) {
        orderDate = new Date(orderDate);
        var deliveryDate = '',
            count = 0;

        while (count < delivery_time) {
            deliveryDate = new Date(orderDate.setDate(orderDate.getDate() + 1));
            if (!selected_office['week_holidays'].includes(deliveryDate.getDay())) {
                count++;
            }
        }

        return deliveryDate;
    }

    function calculateOnlinePrice() {
        /* Code from modal */
        total_book_count = getNumberBookMoreService();
        setNumberOfServiceInDetail(total_book_count);

        updatePaymentForm();
    }

    function onChangeOnlineFile() {
        var clone = $(this).clone();
        clone.attr('name', 'online_file');
        clone.attr('id', 'online_file2');
        $('#online-file-container').html(clone);
    }

    function onPayOnline() {
        if (!checkTermsAccept()) {
            return;
        }

        if (!checkAppLiveMode()) {
            return;
        }

        var msg = $('textarea[name="user_msg"]').val();
        var discountPrice = getServiceDiscount(total_book_count);
        var actualPrice = service_price - discountPrice;
        var totalAmount = actualPrice * total_book_count;

        var data = {
            uid: user_id,
            oid: seller_id,
            sid: service_id,
            book_date: booking_date,
            delivery_date: $('.detail-delivery-date .value').html(),
            service_price: service_price,
            actual_price: actualPrice,
            discount_price: discountPrice,
            number_of_booking: total_book_count,
            total_amount: totalAmount,
            online_delivery_time: delivery_time,
            address: selected_office.city,
            msg: msg,
            office_id: selected_office.office_id,
            provide_online_type: provide_online_type
        };

        $('#payment-form').find("input[name='data']").val(JSON.stringify(data));
        $("#payment-form").submit();

        $(".btn-book").attr('disabled', 'disabled');
    }

    function checkTermsAccept() {
        if ($('#form-payment-terms')[0].checkValidity()) {
            return true;
        } else {
            $('#form-payment-terms')[0].reportValidity();
            return false;
        }
    }

    function getServiceDiscount(bookCount) {
        if (is_offline && (client_payment_type == "{{ Service::PAYMENT_TYPE_ONSITE }}" ||
                client_payment_type == "{{ Service::PAYMENT_TYPE_FREE }}")) {
            return 0;
        }

        if (bookCount > 1) {
            if (discount_multiple > 0) {
                return service_price * (discount_multiple / 100);
            } else {
                return service_price * (discount_single / 100);
            }
        } else {
            if (discount_single > 0) {
                return service_price * (discount_single / 100);
            }
        }

        return 0;
    }

    function getNumberBookMoreService() {
        return Number($('.duration-selector').find('.more-service-count.active').data('value')) || 1;
    }

    function setNumberOfServiceInDetail(bookCount) {
        if (!bookCount || bookCount == 0) {
            $(".detail-number-of-services").find('.value').html("");
        } else {
            var serviceStr = ' {{ trans('main.Service') }}';
            if (bookCount > 1) {
                serviceStr = ' {{ trans('main.Services') }}';
            }

            $(".detail-number-of-services").find('.value').html(bookCount + serviceStr);
        }
    }

    function getPrettyDuration(duration) {
        var durHrs = parseInt(duration / 60);
        var durMins = (duration) % 60;
        var durStr = '';
        if (durHrs != 0) {
            if (durHrs > 1) {
                durStr = durHrs + ' ' + '{{ trans('main.hours') }}';
            } else {
                durStr = durHrs + ' ' + '{{ trans('main.hour') }}';
            }
        }

        if (durMins != 0) {
            durStr += ' ' + durMins + ' ' + '{{ trans('main.min') }}';
        }

        return durStr;
    }

    function addMinutes(time, minsToAdd) {
        function D(J) {
            return (J < 10 ? '0' : '') + J;
        };
        var piece = time.split(':');
        var mins = piece[0] * 60 + +piece[1] + +minsToAdd;

        return D(mins % (24 * 60) / 60 | 0) + ':' + D(mins % 60);
    }
</script>
