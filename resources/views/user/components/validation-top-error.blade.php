@if($errors->all())
    <div class="alert alert-custom alert-light-danger fade show mb-5" role="alert">
        <div class="alert-text">@lang('main.Some data is not correct')</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
@endif