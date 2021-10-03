@php
    use App\Enums\Constants;
    use App\Helpers\TimeHelper;

    $holidays = old('holidays', $office->holidays ?? '');

    if (isset($office)) {
        $name = old('name', $office->name);
        $userId = old('user_id', $office->user_id);
        $address = old('address', $office->address);
        $lat = old('lat', $office->lat);
        $lng = old('lng', $office->lng);
    } else {
        $name = old('name');
        $userId = old('user_id');
        $address = old('address');
        $lat = old('lat', DEFAULT_LAT);
        $lng = old('lng', DEFAULT_LNG);
    }
@endphp

{{-- Extends layout --}}
@extends('admin.layout.default')

@section('styles')
    <style>
        #map {
            min-height: 400px;
        }
    </style>
    {{ Html::style(userAsset('libraries/multidatepicker/css/mdp.css')) }}
    {{ Html::style(userAsset('libraries/multidatepicker/css/pepper-ginder-custom.css')) }}
@endsection

{{-- Content --}}
@section('content')

<div class="row">
    <div class="col-12">
        @include('admin.components.validation-error')

        <div class="card">
            <form method="POST" action="{{ route('admin.office.store') }}">
                @csrf
                @isset($office)
                    <input type="hidden" name="office_id" value="{{ $office->id }}" />
                @endisset

                <div class="card-body">
                    {{-- name --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Name') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('name', $name, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ name --}}

                    {{-- user --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Professional') }}</label>
                        <div class="col-sm-9">
                            {{ Form::select('user_id', $users->pluck('name', 'id'), $userId, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ user --}}

                    {{-- holidays --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Holidays') }}</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="holidays" name="holidays" value="{{ old('holidays') }}" />
                            <div class="full-row" style="width: 100%; height: auto; padding: 0px;">
                                <div id="full-year" class="box" style="width:100%; height: 100%"></div>
                            </div>													
                        </div>
                    </div>
                    {{--./ holidays --}}

                    <!-- week times -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">@lang('Weekly timetables (Select the opening and closing times)')</label>

                        <div class="col-sm-9">
                            @foreach (Constants::WEEK_DAYS as $weekKey => $weekText)
                                <div class="row form-group timetables_container">
                                    <div class="col-md-2 text-right">
                                        <label class="week-label">@lang($weekText): </label>
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
                                            <option value="closed" @if($openField == 'closed' || ('closed' == $sel_time && !$openField)) selected @endif>@lang('Closed')</option>
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
                                            <option value="closed" @if($endField == 'closed' || ('closed' == $sel_time && !$endField)) selected @endif>@lang('Closed')</option>
                                        </select>
                                    </div>
                                    {{--./ end time select --}}

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!--./ week times -->

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Available out of office') }}</label>
                        <div class="col-sm-9">
                            <input type="checkbox" class="make-switch-new" data-size="small" name="office_available" style="margin-top: 13px" value="1">
                        </div>
                    </div>

                    <!-- phone number -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">@lang('Phone')</label>
                        <div class="col-sm-9">
                            <input type="text" name="phone_number" id="phone" class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                                placeholder="@lang('Phone')" value="{{ old('phone_number', $office->phone_number ?? '') }}" />
                        </div>
                    </div>
                    <!--./ phone number -->

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Address') }}</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="address" value="{{ $address }}">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="addrMarker" class="btn btn-default">
                                <span class="glyphicon glyphicon-map-marker"></span> {{ trans('main.Find Address') }}
                            </button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Location') }}</label>
                        <div class="col-sm-9">
                            <div id="map"></div>
                            <input type="hidden" name="lat" value="{{ $lat }}"/>
                            <input type="hidden" name="lng" value="{{ $lng }}"/>
                        </div>
                    </div>

                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.office.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script>
        var holidays = "{{ old('holidays', $office->holidays ?? '') }}";        
    </script>

    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui-1.12.1.js')) }}
    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui.multidatespicker.js')) }}
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_API')}}" type="text/javascript"></script>
    @include('user.pages.office.office-js')

    <script>
        $(function() {
            $('#addrMarker').click(function() {
                var address = jQuery('input[name="address"]').val();
                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var latitude = results[0].geometry.location.lat();
                        var longitude = results[0].geometry.location.lng();
                        markerAddress(latitude, longitude);
                    }
                    else {
                        alert('{{ trans('main.Please input your address correctly!') }}');
                    }
                });
            });
        });

        function markerAddress(lat, lng) {
            /* var myLatlng = new google.maps.LatLng(lat, lng);
            var mapOptions = {
                zoom: 10,
                center: myLatlng
            }
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: 'Office Location'
            }); */
            $("input[name='lat']").val(lat);
            $("input[name='lng']").val(lng);

        }
    </script>
@endsection
