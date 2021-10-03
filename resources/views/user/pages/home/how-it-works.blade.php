@extends('user.layout.default')

@section('styles')
    <style>
        .picture-block {
            padding: 40px 10px;
            border-bottom: 1px solid #ddd;
        }

        .picture-block__content {
            color: grey;
        }

        .picture-block__content strong{
            color: #161c2d;
        }

        .picture-block__cell.picture-block__image-cell {
            text-align: center;
        }

        .picture-block__title {
            font-size: 25px !important;
            font-weight: 600 !important;
        }
    </style>
@stop

@section('content')
<section class="detail_part bg-content">
    <div class="container">
        <div class="text-center">
            <h2 class="title">{{ trans('main.howItWorks.title') }}</h2>
            <p class="m-t-10">{{ trans('main.howItWorks.professionalDescription') }}</p>
        </div>
        <br />

        <div class="users-tab p-4">
            @php $idx = 1; @endphp
            @foreach ($howItWorks as $item)
                @php $idx++; @endphp
                <div class="picture-block">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 order-{{ $idx % 2 + 1 }}">
                                <div class="picture-block__table"> 
                                    <div class="picture-block__cell">  
                                        <h2 class="picture-block__title">{!! $item->title !!}</h2> 
                                        <div class="picture-block__content"> 
                                            <p>{!! $item->content !!}</p>
                                        </div>
                                        @if ($idx == 5)
                                            <p class="text-center">
                                                <a href="{{ route('user.auth.showSignup') }}" class="btn btn-primary change_btn mt-20">{{ trans('main.howItWorks.signup.now') }}</a>
                                            </p>
                                        @endif
                                    </div> 
                                </div> 
                            </div>

                            <div class="col-md-6 order-{{ (($idx + 1) % 2) + 1 }}">
                                <div class="picture-block__table"> 
                                    <div class="picture-block__cell picture-block__image-cell"> 
                                        <img width="250" height="230" class="picture-block__img" src="{{ HTTP_HOWITWORKS_PATH }}{{ $item->image }}" alt="Track campaign performance"> 
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@stop
