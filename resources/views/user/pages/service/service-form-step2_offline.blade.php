<div class="step2-offline-options-container">
    {{-- Duration --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('main.Select duration of your service') <span class="text-danger">*</span></label>
                <select name="duration" class="form-control form-control-lg @error('duration') is-invalid @enderror">
                    <option value="">@lang('main.Select service duration')</option>
                    @for($hours = 0; $hours < \App\Models\Service::MAX_DURATION; $hours++)
                        @for($mins = !$hours ? 15 : 0; $mins < 60; $mins+=15)
                            @php ($currentDuration = $hours * 60 + $mins) @endphp
                            <option {{ old('duration',$service->duration ?? '') == $currentDuration ? 'selected': '' }} value="{{ $currentDuration }}">
                                {{ trans_choice('main.times.hour', $hours, ['h' => $hours]) }} {{ $mins ? trans_choice('main.times.minutes', $mins, ['m' => $mins]) : '' }}
                            </option>
                        @endfor
                    @endfor
                </select>

                @include('user.components.validation-error', ['field' => 'duration'])
                <span class="js-validation-error invalid-feedback d-none"><strong>@lang('main.Enter Duration')</strong></span>
            </div>
        </div>
    </div>
    {{--./ Duration --}}

    {{-- offices for activation --}}
    <div class="activate-office-container" style="display: {{ $officeCount > 1 ? '' : 'none' }}">
        {{-- Offices --}}
        @foreach($offices as $office)
            <div class="form-group office-select-container">
                @if($loop->first)
                    <h5>@lang('main.Select-Office') <span class="text-danger">*</span></h5>
                @endif

                {{-- Office Switch On/Off --}}
                <div class="d-flex">
                    <span class="switch switch-icon">
                        <label>
                        <input type="checkbox" name="office_info[{{ $office->id }}][office_id]" value="{{ $office->id }}"
                            {{ (isset($selectedOffices[$office->id]) || $officeCount <= 1) ? 'checked' : '' }}
                            onchange="onActivateOffice(event)" />
                        <span></span>
                        </label>
                    </span>
                    <label class="col-form-label ml-4">{{ $office->name }}</label>
                </div>
                {{--./ Office Switch On/Off --}}                
            </div>
        @endforeach

        <span class="js-validation-error invalid-feedback d-none"><strong>@lang('main.Activate one office at least')</strong></span>
        {{--./ Offices --}}
    </div>
    {{-- offices for activation --}}
</div>

<div class="step2-offices-container" style="display: none">
    @foreach($offices as $office)
        <div class="office-detail-form office-detail-form-{{ $office->id }}" style="{{ $officeCount > 1 ? 'display: none;' : '' }}">
            <h3 class="{{ $officeCount > 1 ? '' : 'd-none' }}"><span style="font-weight: 500">@lang('main.Settings of location')</span> {{ $office->name }}</h3>
            {{-- Select Number of services same time --}}
            <div class="form-group">
                <div class="label-with-desc">
                    <label>@lang('main.How many services can be booked at the same time?')</label>
                    <div class="desc-content">@lang("main.You'll let customer to book for him and more people at the same time")</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <select name="office_info[{{ $office->id }}][book_count]" class="form-control form-control-lg sel-book-count">
                            <option value="1" {{ ($selectedOffices[$office->id]['book_count'] ?? null) == 1 ? 'selected' : '' }}>@lang('main.Drop down At same time services')</option>
                            @foreach([2,3,4,5] as $val)
                                <option value="{{ $val }}" {{ ($selectedOffices[$office->id]['book_count'] ?? null) == $val ? 'selected' : '' }} >{{ $val }} @lang('main.Services bookable at the same time')</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{--./ Select Number of services same time --}}

            {{-- Select Number of services period  --}}
            <div class="form-group">
                <div class="label-with-desc">
                    <label>@lang("main.How many services that can be booked consecutively?")</label>
                    <div class="desc-content">@lang("main.You will allow to book more services in sequence")</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <select name="office_info[{{ $office->id }}][book_consecutively]" class="form-control form-control-lg sel-book-period">
                            <option value="1" {{ ($selectedOffices[$office->id]['book_consecutively'] ?? null) == 1 ? 'selected' : '' }}>@lang('main.Select the number of services bookable consecutively')</option>
                            @foreach([2,3,4,5,6,7,8] as $val)
                                <option value="{{ $val }}" {{ ($selectedOffices[$office->id]['book_consecutively'] ?? null) == $val ? 'selected' : '' }}>{{ $val }} {{ trans('main.Consecutive services') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{--./ Select Number of services period  --}}

            {{-- provide service where --}}
            <div class="form-group">
                <label>@lang("main.Where you will provide this service?") <span class="text-danger">*</span></label>
                <div class="onsite_type radio-inline mt-3">
                    @foreach($on_site_types as $onsiteType)
                        <label class="radio mr-8">
                            <input type="radio" name="office_info[{{ $office->id }}][onsite_type]" value="{{ $onsiteType['type_id'] }}" 
                                {{ ($selectedOffices[$office->id]['onsite_type'] ?? null) == $onsiteType['type_id'] ? 'checked' : '' }}/>
                            <span></span>@lang('main.' . $onsiteType['name'])
                        </label>
                    @endforeach
                </div>

                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter provide onsite or offsite')</strong></span>
            </div>
            {{--./ provide service where --}}

            {{-- provide range  --}}
            <div class="provide-range-container">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>@lang("main.How many Kilometers you can travel?")</label> <span class="text-danger">*</span>

                        <input type="text" class="input-decimal form-control provide-range-input" name="office_info[{{ $office->id }}][provide_range]"
                            value="{{ $selectedOffices[$office->id]['provide_range'] ?? null }}" placeholder="@lang('main.Enter maximum kilometer that you can travel')" />
                        
                        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter provide range')</strong></span>
                    </div>
                </div>
            </div>
            {{--./ provide range  --}}
        </div>
    @endforeach()
</div>