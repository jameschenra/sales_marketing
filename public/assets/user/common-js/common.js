$(function () {
    $("a#btn-delete-item").on('click', function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(function (result) {
            if (result.value) {
                window.location.href = url;
            }
        });
    });

    $('.navbar-toggle').on('click', function (event) {
        if ($(this).hasClass('open')) {
            $('.content-layout-container').css('overflow', 'hidden');
        } else {
            $('.content-layout-container').css('overflow', '');
        }
    });

    initDatePickerLanguage();
});

function showLoadingProgress() {
    $('#ajax-loader').addClass('d-flex').removeClass('d-none');
}

function hideLoadingProgress() {
    $('#ajax-loader').addClass('d-none').removeClass('d-flex');
}

function detectMob() {
    return ((window.innerWidth <= 600));
    // return ((window.innerWidth <= 800) && (window.innerHeight <= 600));
}

function checkAppLiveMode() {
    $('.client-err-msg-wrapper').addClass('d-none');
    if (APP_MODE != 'LIVE') {
        $('.client-err-msg-wrapper').removeClass('d-none');
        window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
        return false;
    }

    return true;
}

function generateTopAlert(msg = 'Warning', type = 'success') {
    var alertContent = '<div class="alert-wrapper container mt-5">'
        + '<div class="d-flex justify-content-center">'
        + '<div class="alert alert-' + type + ' global-error" role="alert">'
        + msg
        + '</div></div></div>';

    $('.content-container .alert-wrapper').remove();
    $(".content-container").prepend(alertContent);
    window.scrollTo(0, 0);
}

function notifyAlert(msg = 'Warning', type = "success") {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    if (type == 'success') {
        toastr.success(msg);
    } else if (type == 'error') {
        toastr.error(msg);
    }
}

function initDatePickerLanguage() {
    $.fn.datepicker.dates['it'] = {
        days: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
        daysShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
        daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
        months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
        monthsShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
        today: "Oggi",
        monthsTitle: "Mesi",
        clear: "Cancella",
        weekStart: 0,
        format: "dd/mm/yyyy"
    };

    $.fn.datepicker.dates['es'] = {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        monthsTitle: "Meses",
        clear: "Borrar",
        weekStart: 0,
        format: "dd/mm/yyyy"
    };
}