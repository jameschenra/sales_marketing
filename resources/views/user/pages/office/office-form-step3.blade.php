@php
    use App\Helpers\TimeHelper;
    use App\Enums\Constants;    
@endphp

<!-- week times -->
<div class="form-group timetables-wrapper">
    <label class="mb-5">@lang('main.Opening Hours')</label> @lang('main.Opening Hours description')

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