{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/profile/detail.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.My favorites')</h3>

        {{-- Nav tabs --}}
        <ul class="nav nav-tabs" id="favourite-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-favourite-professional">
                    <span class="nav-text">@lang('main.Professionals')</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab-2" data-toggle="tab" href="#tab-favourite-service">
                    <span class="nav-text">@lang('main.Services')</span>
                </a>
            </li>
        </ul>
        {{--./ Nav tabs --}}

        {{-- Tab contents --}}
        <div class="tab-content mt-5">
            <div class="tab-pane fade show active" id="tab-favourite-professional" role="tabpanel" aria-labelledby="favourite-professional-tab">
                @forelse($professionals as $professional)
                    <div>
                        @include('user.pages.professional.detail-profession-row', ['user' => $professional->professional])
                        <br />
                        <p style="word-break: break-all;">
                            <strong>Note:</strong> {{ $professional->note }}
                        </p>
                        <hr />
                    </div>
                @empty
                    <p class="text-center">@lang('main.No Professionals added yet to your favorites')</p>
                @endforelse
            </div>

            <div class="tab-pane fade" id="tab-favourite-service" role="tabpanel" aria-labelledby="favourite-service-tab">
                @forelse($services as $service)
                    <div>
                        @include('user.pages.service.detail-service-row', ['service' => $service->service])
                        <br />
                        <p style="word-break: break-all;">
                            <strong>Note:</strong> {{ $service->note }}
                        </p>
                        <hr />
                    </div>
                @empty
                    <p class="text-center">@lang('main.No Services added yet to your favorites')</p>
                @endforelse
            </div>
        </div>
        {{--./ Tab contents --}}
    </div>
</section>
@endsection

@section('scripts')
    {{ Html::script(userAsset('libraries/readmore.js')) }}
    @include('user.include-js.readmore-js')
    @include('user.include-js.favourite-js')
@endsection
