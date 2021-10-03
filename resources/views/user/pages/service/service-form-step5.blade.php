<div class="form-group text-center">
    <p class="text-muted font-size-lg">@lang('main.We are showing this image for your service')</p>
    <img id="service-photo" src="{{ HTTP_SERVICE_PATH . old('photo', $service->photo ?? DEFAULT_PHOTO) }}" alt="service photo" />
</div>

{{-- photo  --}}
<div class="form-group text-center">
    <label>@lang("main.Do you want to change image for your service?")</label>

    <div>
        <input type="file" accept=".png, .jpg, .jpeg" id="photo-input" class="d-none" />
        <input type="hidden" name="photo" value="{{ old('photo', $service->photo ?? '') }}" id="photo-file-name" />
        <button type="button" class="btn btn-primary" onclick="onChangeImage()">@lang('main.Change image')</button>
    </div>
    
    @include('user.components.validation-error', ['field' => 'photo'])
    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter provide range')</strong></span>
</div>
{{--./ photo --}}