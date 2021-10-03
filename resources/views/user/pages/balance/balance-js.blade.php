<script>
var userWallet = {!! $user->wallet_balance !!};

$(function() {
    $('#withdraw-form-submit-btn').on('click', onSubmitWithdraw);
});

function onSubmitWithdraw(event) {
    event.preventDefault();

    $('#withdraw-amount-error').addClass('d-none').removeClass('d-block');

    var withDrawForm = $('#withdraw-form')[0];
    if(! withDrawForm.checkValidity()) {
        withDrawForm.reportValidity();
        return;
    }

    var amount = $('#withdraw-amount').val();
    if (amount > userWallet) {
        $('#withdraw-amount-error').html("{{ trans('main.balance.withdraw.validation.amount-max',
                ['available-balance-value' => number_format($user->wallet_balance, 2)]
            ) }}");
        $('#withdraw-amount-error').addClass('d-block').removeClass('d-none');
        return;
    } else if (amount < 10) {
        $('#withdraw-amount-error').html("{{ trans('main.balance.withdraw.validation.amount-min') }}");
        $('#withdraw-amount-error').addClass('d-block').removeClass('d-none');
        return;
    }

    ajax_post("{{ route('user.balance.withdraw_2fa_request') }}",
        {
            email: $('#withdraw-email').val(),
            withdraw_amount: amount
        }, function () {
            $('#confirm-withdraw-modal').modal();
        }
    );
}

function checkBookingConfirmCode() {
    var code = $('#modal_2fa_code').val();
    ajax_post("{{ route('user.balance.withdraw_2fa_check') }}",
        {
            code: code
        }, function (data) {
            if (data.data) {
                if (data.data.result == 'success') {
                    $('#confirm-withdraw-modal').modal('hide');
                    $('#2fa_code').val(code);
                    $('#withdraw-form').submit();
                    return;
                }
            }

            alert("{{ trans('main.balance.withdraw.messages.error_2fa_code') }}");
        }
    );
}
</script>