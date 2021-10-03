@extends('user.layout.default')

@section('styles')
    <style>
        .picture-block__content {
            color: grey;
        }

        .picture-block__content strong{
            color: #161c2d;
        }

        .picture-block__title {
            font-size: 25px !important;
            font-weight: 600 !important;
        }

        .order-1 .picture-block__image-cell {
            text-align: right;
        }

        .signup-card {
            background-color: rgba(11,101,254,1);
        }

        .signup-card form .form-group label{
            color: white;
        }

        .btn-continue {
            border: 1px solid white;
            color:white;
        }
    </style>
@stop

@section('content')
<section class="detail_part bg-content">
    <div class="row mb-16">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="d-flex flex-column flex-sm-row justify-content-center">
                <div class="mr-sm-8 mx-auto mx-sm-0 mt-0 mt-sm-32" style="width: 300px;">
                    <h2 class="picture-block__title">@lang('main.Get anywhere')</h2> 
                    <div class="picture-block__content"> 
                        <p>@lang('main.Find new customers wherever you want')</p>
                    </div>
                </div>
                <div class="signup-card card mx-auto mx-sm-0" style="width: 350px;">
                    <div class="card-body px-8 py-10">
                        <h2 class="text-center text-white">@lang('main.Sign Up')</h2>
                        <br>
                        <form method="POST" action="{{ route('user.auth.registerProfessional') }}">
                            @csrf

                            <div class="form-group">
                                <label>@lang('main.What service do you provide?')</label>
                                <select name="category_name" class="form-control">
                                    <option value="">@lang('main.Select a category of services')</option>
                                    @foreach($categories as $category)
                                        @if($category->slug != 'other')
                                            <option value="{{$category->slug}}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>@lang('main.Where?')</label>
                                <input id="city-selector" type="text" class="form-control" placeholder="@lang('main.search-box-city')" />
                            </div>

                            <div class="form-group">
                                <label>@lang('main.Your email')</label>
                                <input type="email" name="email" class="form-control" placeholder="@lang('main.Enter your email')" />
                            </div>

                            <div class="form-group">
                                <label>@lang('main.Password')</label>
                                <input type="password" name="password" class="form-control" placeholder="@lang('main.Enter a password')" />
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-continue">@lang('main.Continue')</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    @foreach ($howItWorks as $item)
        <div class="row mb-16">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="d-flex flex-column flex-sm-row">
                    <div class="col-md-6 order-{{ (($loop->iteration + 1) % 2) + 1 }}">
                        <div class="picture-block__table"> 
                            <div class="picture-block__cell">  
                                <h2 class="picture-block__title">{!! $item->title !!}</h2> 
                                <div class="picture-block__content"> 
                                    <p>{!! $item->content !!}</p>
                                </div>
                            </div> 
                        </div> 
                    </div>
        
                    <div class="col-md-6 order-{{ $loop->iteration % 2 + 1 }}">
                        <div class="picture-block__table"> 
                            <div class="picture-block__cell picture-block__image-cell"> 
                                <img width="250" height="230" class="picture-block__img" src="{{ HTTP_HOWITWORKS_PATH }}{{ $item->image }}" alt="Track campaign performance"> 
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    @endforeach

    <div class="text-center">
        <a class="btn btn-primary" style="width: 150px;" href="{{ route('user.auth.showSignup') }}">@lang('main.Sign Up')</a>
    </div>
</section>
@stop

@section('scripts')
    <script>
        var mapAPIKey = "{{ env('MAP_API') }}";
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API') }}&libraries=places"></script>
    {{ Html::script(userAsset('common-js/address_autocomplete.js')) }}
    
    <script>
        $(function() {
            getAutocompleteAddress('city-selector');
        });
    </script>
@endsection