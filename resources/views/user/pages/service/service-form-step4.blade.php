{{-- offer video call --}}
<div class="form-group">
    <div class="label-with-desc">
        <label>@lang("main.Do you want to offer a video call service?") <span class="text-danger">*</span></label>
        <div class="desc-content">@lang('main.Price for videocall is showing seperately and will have same duration')</div>
    </div>
    
    <div class="radio-inline has_video_call">
        <label class="radio mr-8">
            <input type="radio" name="has_video_call" value="1" disabled />
            <span></span>@lang('main.Yes, I\'ll offer it')
        </label>
        <label class="radio mr-8">
            <input type="radio" name="has_video_call" value="0" checked disabled />
            <span></span>@lang('main.No, thanks')
        </label>
    </div>

    @include('user.components.validation-error', ['field' => 'has_video_call'])
    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter offer video call')</strong></span>
    <span class="desc-content" role="alert"><strong>@lang('main.This option not available')</strong></span>
</div>
{{--./ offer video call --}}