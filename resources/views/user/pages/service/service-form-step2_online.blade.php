<div class="online-option-container" style="display: none">
    {{-- Offices --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('main.Select one office') <span class="text-danger">*</span></label>

                <select name="online_office_id" class="form-control form-control-lg @error('online_office_id') is-invalid @enderror">
                    <option value="">@lang('main.Select Office')</option>
                    @foreach($offices as $office)
                        <option {{ old('online_office_id', $service->online_office_id ?? '') == $office->id ? 'selected': '' }} value="{{ $office->id }}">
                            {{ $office->name }}
                        </option>
                    @endforeach
                </select>

                @include('user.components.validation-error', ['field' => 'online_office_id'])
                <span class="js-validation-error invalid-feedback d-none"><strong>@lang('main.Select one office at least')</strong></span>
            </div>
        </div>
    </div>
    {{--./ Offices --}}
        
    <div class="online-service-detail" style="display: none;">
        {{-- Delivery time --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('main.Once the service has been ordered, when it will be delivered?') <span class="text-danger">*</span></label>
                    <select name="online_delivery_time" class="form-control form-control-lg @error('online_delivery_time') is-invalid @enderror">
                        <option value="">@lang('main.Select delivery time')</option>
                        @for($deliveryTime = 1; $deliveryTime <= \App\Models\Service::MAX_DELIVERY_TIME; $deliveryTime++)
                            <option {{ old('online_delivery_time', $service->online_delivery_time ?? '') == $deliveryTime ? 'selected': '' }} value="{{ $deliveryTime }}">
                                {{ trans_choice('main.times.day', $deliveryTime, ['d' => $deliveryTime]) }}
                            </option>
                        @endfor
                    </select>

                    @include('user.components.validation-error', ['field' => 'online_delivery_time'])
                    <span class="js-validation-error invalid-feedback d-none"><strong>@lang('main.Enter delivery time')</strong></span>
                </div>
            </div>
        </div>
        {{--./ Delivery time --}}

        {{-- Select Number of services same time --}}
        <div class="form-group">
            <div class="label-with-desc">
                <label>@lang("main.How many services can be ordered at the same time?")</label>
                <div class="desc-content">@lang("main.You'll let customer order more than one service. E.g. order n.2 logo at the same time")</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <select name="online_book_count" class="form-control form-control-lg sel-book-count">
                        <option value="1" {{ old('online_book_count', $service->online_book_count ?? '') == 1 ? 'selected' : '' }}>@lang('main.Services orderable at the same time')</option>
                        @foreach([2,3,4,5] as $val)
                            <option value="{{ $val }}" {{ old('online_book_count', $service->online_book_count ?? '') == $val ? 'selected' : '' }}>{{ $val }} @lang('main.Services orderable at the same time')</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        {{--./ Select Number of services same time --}}

        {{-- Revision --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>@lang('main.How many revisions are allowed to request?') <span class="text-danger">*</span></label>
                    <select name="online_revision" class="form-control form-control-lg @error('online_revision') is-invalid @enderror">
                        <option value="">@lang('main.Revisions')</option>
                        @for($revision = 1; $revision <= \App\Models\Service::MAX_REVISIONS; $revision++)
                            <option {{ old('revision', $service->online_revision ?? '') == $revision ? 'selected': '' }} value="{{ $revision }}">
                                {{ trans_choice('main.revision', $revision, ['r' => $revision]) }}
                            </option>
                        @endfor
                        <option {{ old('revision', $service->online_revision ?? '') == -1 ? 'selected': '' }} value="-1">
                            {{ trans('main.Unlimited revisions') }}
                        </option>
                    </select>

                    @include('user.components.validation-error', ['field' => 'online_revision'])
                    <span class="js-validation-error invalid-feedback d-none"><strong>@lang('main.Enter Revision')</strong></span>
                </div>
            </div>
        </div>
        {{--./ Revision --}}
    </div>
</div>