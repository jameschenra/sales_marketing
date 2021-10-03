@php
    use App\Enums\Constants;
    use App\Helpers\TimeHelper;

    if ($office) {
        $officePhoneNumber = old('phone_number', $office->phone_number);
        $officeCountryId = old('country_id', $office->country_id);
        $hasCalendar = old('has_calendar', $office->has_calendar);
    } else {
        $user = auth()->user();
        $officePhoneNumber = old('phone_number', $user->phone);
        $officeCountryId = old('country_id', $user->detail->country_id);
        $hasCalendar = old('has_calendar');
    }
@endphp

@section('partial-styles')
    <style>
        #map {
            min-height: 400px;
        }
    </style>
    @include('user.include-plugins.input-tel.input-tel-css')
    {{ Html::style(userAsset('libraries/multidatepicker/css/mdp.css')) }}
    {{ Html::style(userAsset('libraries/multidatepicker/css/pepper-ginder-custom.css')) }}
@endsection

<form class="form" method="POST" action="{{ route('user.office.store') }}" id="form-office">
    @csrf
    <input type="hidden" name="office_id" value="{{ $office ? $office->id : null }}" />

    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        @if($mode == 'edit')
            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Edit Office')</h3>
        @else
            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Enter Office')</h3>
        @endif
        <div class="row">
            <div class="col-xl-6">
                <!-- office name -->
                <div class="form-group">
                    <label>@lang('main.Office name') <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                        placeholder="@lang('main.Enter office name')" value="{{ old('name', $office->name ?? '') }}" />

                    @include('user.components.validation-error', ['field' => 'name'])
                </div>
                <!--./ office name -->
            </div>

            <div class="col-xl-6">
                <!-- phone number -->
                <div class="form-group">
                    <label>@lang('main.Office telephone') <span class="text-danger">*</span></label>
                    <input type="text" name="phone_number" id="phone" class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                        placeholder="@lang('main.Enter office telephone')" value="{{ old('phone_number', $office->phone_number ?? '') }}" />
                    
                    @include('user.components.validation-error', ['field' => 'phone_number', 'custom-control' => true])
                </div>
                <!--./ phone number -->
            </div>
        </div>
        <br />

        <!-- week times -->
        <div class="form-group timetables-wrapper">
            <label class="mb-5">@lang('main.Opening Hours')</label>
            <div class="d-block d-sm-inline-block">@lang('main.Opening Hours description')</div>

            @foreach (Constants::WEEK_DAYS as $weekKey => $weekText)
                <div class="row form-group timetables_container">
                    <div class="col-md-2 text-right">
                        <label class="week-label">@lang('main.' . $weekKey): </label>
                    </div>

                    {{-- open time select --}}
                    <div class="col-md-3 offset-md-1">
                        @php
                            $openFieldName = $weekKey . '_start';
                            $openField = old($openFieldName, $office->opening[$openFieldName] ?? '');
                        @endphp
                        <select name="{{ $openFieldName }}" class="form-control form-control-lg" onchange="disableClosingTimes(this);">
                            @foreach(TimeHelper::getOfficeTimes('OPEN') as $time)
                                @php
                                    $sel_time = (empty($openField) ? ($weekKey == 'sat' || $weekKey == 'sun' ?  'closed' : '09:00') : $openField);
                                    $selected = $time == $openField ? 'selected' : '';
                                    if(! $openField && $time == $sel_time) {
                                        $selected = 'selected';
                                    }
                                @endphp
                                <option value="{{ $time }}" {{ $selected }}>{{ $time }}</option>
                            @endforeach
                            <option value="closed" @if($openField == 'closed' || ('closed' == $sel_time && !$openField)) selected @endif>@lang('main.Closed')</option>
                        </select>
                    </div>
                    {{--./ open time select --}}

                    {{-- end time select --}}
                    <div class="col-md-3">
                        @php
                            $endFieldName = $weekKey . '_end';
                            $endField = old($endFieldName, $office->opening[$endFieldName] ?? '');
                        @endphp
                        <select name="{{ $endFieldName }}" id="sel-end-{{ $weekKey }}" class="form-control form-control-lg">
                            @foreach(TimeHelper::getOfficeTimes('END') as $time)
                                @php
                                    $sel_time = (empty($endField) ? ($weekKey == 'sat' || $weekKey == 'sun' ?  'closed' : '18:00') : $endField);
                                    $selected = $time == $endField ? 'selected' : '';
                                    if(!$endField && $time == $sel_time) {
                                        $selected = 'selected';
                                    }
                                @endphp
                                <option value="{{ $time }}" {{ $selected }}>{{ $time }}</option>
                            @endforeach
                            <option value="closed" @if($endField == 'closed' || ('closed' == $sel_time && !$endField)) selected @endif>@lang('main.Closed')</option>
                        </select>
                    </div>
                    {{--./ end time select --}}

                </div>
            @endforeach
        </div>
        <!--./ week times -->
        <br />

        {{-- enable calendar --}}
        <div class="form-group d-none">
            <label>@lang("main.Will you enable calendar?") <span class="text-danger">*</span></label><br />
            
            <div class="radio-inline has_calendar">
                <label class="radio mr-8">
                    <input type="radio" name="has_calendar" value="0" {{ $hasCalendar == '0' || !$hasCalendar ? 'checked' : ''}} />
                    <span></span>@lang("main.No, I will not use calendar")
                </label>
                <label class="radio mr-8">
                    <input type="radio" name="has_calendar" value="1" {{ $hasCalendar == '1' ? 'checked' : ''}} />
                    <span></span>@lang("main.Yes, I'll use calendar")
                </label>
            </div>

            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Select calendar visibility') }}</strong></span>
        </div>
        {{--./ enable calendar --}}

        <!-- full calendar -->
        <div class="form-group calendar-container">
            <label>@lang('main.Holidays')</label> @lang('main.Holidays description')
            <input type="hidden" id="holidays" name="holidays" value="{{ old('holidays') }}" />
            <div class="full-row" style="width: 100%; height: auto; padding: 0px;">
                <div id="full-year" class="box" style="width:100%; height: 100%"></div>
            </div>
        </div>
        <!--./ full calendar -->

        <div class="row">
            <div class="col-xl-6">
                <!-- country -->
                <div class="form-group">
                    <label>@lang('main.Country') <span class="text-danger">*</span></label>
                    <select name="country_id" class="form-control selectpicker @error('country_id') is-invalid @enderror"
                        data-size="7" data-live-search="true">
                        <option value="">@lang('main.Select office country')</option>
                        @foreach($countries as $c)
                            <option value="{{$c->id}}" {{$c->id == old('country_id', $office->country_id ?? null) ? 'selected':''}}>{{$c->short_name}}</option>
                        @endforeach
                    </select>

                    @include('user.components.validation-error', ['field' => 'country_id'])
                </div>
                <!--./ country -->
            </div>
            <div class="col-xl-6">
                <!-- region -->
                <div class="form-group">
                    <label>@lang('main.region') <span class="text-danger">*</span></label>
                    <select name="region_id" class="form-control selectpicker @error('region_id') is-invalid @enderror"
                        data-size="7" data-live-search="true">
                        <option value="">@lang('main.Enter office region')</option>
                    </select>
                    
                    @include('user.components.validation-error', ['field' => 'region_id'])
                </div>
                <!--./ region -->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <!-- city -->
                <div class="form-group">
                    <label>@lang('main.city') <span class="text-danger">*</span></label>
                    <select name="city_id" class="form-control selectpicker @error('region_id') is-invalid @enderror"
                        data-size="7" data-live-search="true">
                        <option value="">@lang('main.Enter office city')</option>
                    </select>
        
                    @include('user.components.validation-error', ['field' => 'city_id'])
                </div>
                <!--./ city -->
            </div>
            <div class="col-xl-6">
                <!-- address -->
                <div class="form-group">
                    <label>@lang('main.Office address') <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                        placeholder="@lang('main.Enter office address')" value="{{ old('address', $office->address ?? '') }}" />
                    
                    @include('user.components.validation-error', ['field' => 'address'])
                </div>
                <!--./ address -->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <!-- zip code -->
                <div class="form-group">
                    <label>@lang('main.Zip Code') <span class="text-danger">*</span></label>
                    <input type="text" name="zip_code" class="form-control form-control-lg @error('zip_code') is-invalid @enderror" 
                        placeholder="@lang('main.Enter zip code')" value="{{ old('zip_code', $office->zip_code ?? '') }}" />

                    @include('user.components.validation-error', ['field' => 'zip_code'])
                </div>
                <!--./ zip code -->
            </div>
        </div>

        <div class="form-group">
            <div id="map"></div>
            <input type="hidden" name="lat"/>
            <input type="hidden" name="lng"/>
        </div>
    </div>

    <input type="hidden" name="mode" value="{{ $mode }}" />
    <!--begin::Wizard Actions-->
    @if($mode == 'edit')
        <div class="border-top mt-5 pt-10">
            <button type="submit" onclick="formSubmit(event)" class="btn btn-primary font-weight-bold px-9 py-2 float-right">@lang('main.Update')</button>
        </div>
    @else
        <div class="border-top mt-5 pt-10">
            <button type="submit" onclick="formSubmit(event)" class="btn btn-primary font-weight-bold px-9 py-2 float-right">@lang('main.Save')</button>
         </div>
    @endif
    <!--end::Wizard Actions-->
</form>

@section('partial-scripts')
    <script>
        var is_office_wizard = false;
    </script>
    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui-1.12.1.js')) }}
    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui.multidatespicker.js')) }}
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_API')}}" type="text/javascript"></script>
    @include('user.include-plugins.input-tel.input-tel-js')
    @include('user.pages.office.office-js')
@endsection