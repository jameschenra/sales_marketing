<script>
    $(function() {
        $('#chat-message-form button[type=submit]').on('click', function (event) {
            onSubmitMessageForm($(this));
        });
    });

    function submitExtendDelivery(event) {
        event.preventDefault();
        var extendForm = $("#form-extend-delivery-date");
        if(!extendForm[0].checkValidity()) {
            extendForm.find(':submit').click();
            return;
        }

        var newDeliveryDate = $('#new-delivery-date').val();
        if (!newDeliveryDate) {
            alert("{{ trans('main.Please select new delivery date') }}");
            return;
        }

        $('#form-extend-delivery-date').submit();
    }

    function onSubmitMessageForm(btnObj) {
        if (btnObj.attr('data-no-validation') != 1) {
            if (!$('#chat-message-form')[0].checkValidity()) {
                if ($('#chat-message-form')[0].reportValidity) {
                    setTimeout(() => {
                        $('#chat-message-form')[0].reportValidity();
                    }, 500);
                }
                return;
            }
        }

        btnObj.attr('disabled', true);
        $('#chat-message-form button').attr('disabled', true);
        var submitUrl = btnObj.attr('data-action-url');
        $('#chat-message-form').attr('action', submitUrl);
        $('#chat-message-form').submit();
    }

    function onCancelOnlineOrder(event) {
        Swal.fire({
            title: "{{ trans('main.cancel_online_service_refund_full', ['refund_price' => $book->total_amount, 'buyer_name' => $book->user->name]) }}",
            showCancelButton: true,
            confirmButtonText: "{{ trans('main.confirm_pop-up') }}",
            cancelButtonText: "{{ trans('main.cancel_pop-up') }}"
        }).then(function(result) {
            if (result.value) {
                onSubmitMessageForm($(event.target));
            }
        });
    }
</script>