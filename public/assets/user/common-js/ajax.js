function ajax_get(url, callback = null) {
    $('#ajax-loader').addClass('d-flex').removeClass('d-none');

    axios.get(url)
        .then(function (response) {
            if (callback) {
                callback(null, response);
            }
            $('#ajax-loader').addClass('d-none').removeClass('d-flex');
        })
        .catch(function (error) {
            $('#ajax-loader').addClass('d-none').removeClass('d-flex');
            openAjaxErrorModal();
        });
}

function ajax_post(url, data, callback = null, dataType = 'application/json', callbackError = null) {
    $('#ajax-loader').addClass('d-flex').removeClass('d-none');

    axios.post(url, data,
            {
                headers: {
                    'Content-Type': dataType
                }
            }
        )
        .then(function (response) {
            if (callback) {
                callback(response);
            }
            $('#ajax-loader').addClass('d-none').removeClass('d-flex');
        })
        .catch(function (error) {
            $('#ajax-loader').addClass('d-none').removeClass('d-flex');
            if (callbackError) {
                callbackError(error);
            } else {
                openAjaxErrorModal();
            }
        });
}

function openAjaxErrorModal() {
    swal.fire({
        title: '<span class="text-danger">Error occured while processing...</span>',
        type: 'danger',
        buttonsStyling: false,
        confirmButtonText: 'Close!',
        confirmButtonClass: 'btn btn-danger font-weight-bold'
    });
    // $('#ajax-error-modal').modal('show');
}