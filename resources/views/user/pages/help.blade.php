@extends('user.layout.default')

@section('styles')
@stop

@section('content')
<section class="detail_part bg-content d-flex align-items-center">
    <div class="container">
        <h2 class="text-center">{{ trans('main.Help_Help') }}</h2>
        <p class="text-muted">@lang('main.Help page description')</p>
        <br />

        <div class="row justify-content-center">
            {{-- help categories --}}
            <div class="col-lg-4 col-md-5 col-12 d-none d-md-block">
                
                <div class="rounded shadow p-8 sticky-bar">
                    <ul class="list-unstyled mb-0">
                        @foreach($help_types as $helpType)
                            @php $linkUrl = str_replace(' ', '', $helpType->name); @endphp
                            <li class="{{ $loop->first ? '' : 'mt-4' }}">
                                <a href="#{{ $linkUrl }}" class="mouse-down h5 text-dark">{{$helpType->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{--./ help categories --}}

            {{-- help contents --}}
            <div class="col-lg-8 col-md-7 col-12">
                @foreach($help_types as $helpType)
                    @php $linkUrl = str_replace(' ', '', $helpType->name); @endphp
                    <div class="section-title" id="{{ $linkUrl }}">
                        <h4>{{ $helpType->name }}</h4>
                    </div>

                    {{-- help contents accordion --}}
                    <div class="faq-content mt-4 pt-2">
                        <div class="accordion" id="accordion{{ $linkUrl }}">
                            @foreach($helpType->help_contents as $content)
                                <div class="card border-0 rounded mb-2">
                                    <a data-toggle="collapse" href="#collapse{{ $content->id }}" class="faq position-relative collapsed"
                                        aria-expanded="false" aria-controls="collapseone">
                                        <div class="card-header border-0 bg-light p-3 pr-5" id="headingfifone">
                                            <h6 class="title mb-0">{{ $content->title }}</h6>
                                        </div>
                                    </a>
                                    <div id="collapse{{ $content->id }}" class="collapse" aria-labelledby="headingfifone"
                                        data-parent="#accordion{{ $linkUrl }}">
                                        <div class="card-body px-2 py-4">
                                            <p class="text-muted mb-0 faq-ans">{!! $content->content !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{--./ help contents accordion --}}

                @endforeach
            </div>
            {{--./ help contents --}}
        </div>
    </div>
</section>
@stop
