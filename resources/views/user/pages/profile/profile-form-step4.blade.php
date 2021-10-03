<div class="row mt-4">
    <!-- photo -->
    <div class="col-md-6 form-group text-center">
        <div class="image-input image-input-outline" id="kt_image_1">
            <div class="image-input-wrapper" style="background-image: url(/upload/user/{{ $userPhoto ?: DEFAULT_PHOTO }})"></div>

            <label class="btn btn-primary btn-change-photo mt-4" data-action="change">
                @lang("main.Uplaod your photo")
                <input type="file" accept=".png, .jpg, .jpeg" id="photo-input" />
                <input type="hidden" name="photo" value="{{ old('photo', $user->detail->photo) }}" id="photo-file-name" />
            </label>

            @include('user.components.validation-error', ['field' => 'photo', 'custom-control' => true])
        </div>

        <span class="js-validation-error invalid-feedback d-none" role="alert">@lang("main.Select photo")</span>
    </div>
    <!--./ photo -->

    <!-- hourly rate -->
    <div class="col-xl-6 form-group d-none">
        <label>@lang('main.Hourly Rate')</label> @lang('main.is_optional')
        <input type="text" class="form-control form-control-lg input-decimal" name="hourly_rate" placeholder="" value="{{ old('hourly_rate', $user->hourly_rate) }}" />
    </div>
    <!--./ hourly rate -->
</div>