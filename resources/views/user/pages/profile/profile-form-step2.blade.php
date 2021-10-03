@php
    use App\Models\EnrollType;
@endphp

<!-- enroll professional or institute -->
<div class="form-group">
    <label>@lang('main.select enrolled type') <span class="text-danger">*</span></label>
    <div class="row radio-inline enroll-type">
        @foreach($enroll_types as $enrollType)
            <div class="col-md-6 mt-2">
                <label class="radio">
                    <input type="radio" id="enrolledRadio{{ $loop->iteration }}" name="enroll_type" class="custom-control-input" value="{{ $enrollType->id }}"
                        {{ $curEnrollType == $enrollType->id ? 'checked='.'"'.'checked'.'"' : '' }} />
                    <span></span>@lang('main.' . $enrollType->trans_abbr)
                </label>
            </div>
        @endforeach
    </div>

    @include('user.components.validation-error', ['field' => 'enroll_type'])
    <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Select enroll type')</span>
</div>
<!--./ enroll professional or institute -->

<div class="order-detail-container {{ ($curEnrollType == EnrollType::NOT_ENROLLED || $curEnrollType == null) ? 'd-none' : '' }}">
    <!-- association -->
    <div class="form-group">
        <label>@lang('main.EnrollTypeName') <span class="text-danger">*</span></label>
        <select name="association_id" class="form-control form-control-lg @error('association_id') is-invalid @enderror">
            <option value="">@lang('main.EnrollTypeNamePlaceholder')</option>
            @foreach($associations as $association)
                <option value="{{ $association->id }}" {{ $associationId == $association->id  ? 'selected':''}}>{{ $association->name }}</option>
            @endforeach
        </select>

        @include('user.components.validation-error', ['field' => 'association_id'])
        <span class="js-validation-error invalid-feedback d-none" role="alert">@lang("main.Select association")</span>
    </div>
    <!--./ association -->

    <div class="row">
        <!-- city -->
        <div class="col-md-6 form-group">
            <label>@lang('main.city_label') <span class="text-danger">*</span></label>
            <input id="city" class="form-control @error('city') is-invalid @enderror"
                name="city" placeholder="@lang('main.city_label_placeholder')"
                value="{{ old('city', $user->detail->city) }}"/>

            @include('user.components.validation-error', ['field' => 'city'])
            <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Enter city')</span>
        </div>
        <!--./ city -->

        <!-- registration number -->
        <div class="col-md-6 form-group">
            <label>@lang('main.Registration Number')</label> @lang('main.is_optional')
            <input id="reg_number" class="form-control @error('reg_number') is-invalid @enderror"
                name="reg_number" placeholder="@lang('main.Enter your registration number')"
                value="{{ old('reg_number', $user->detail->reg_number) }}"/>

            @include('user.components.validation-error', ['field' => 'reg_number'])
        </div>
        <!--./ registration number -->
    </div>
</div>