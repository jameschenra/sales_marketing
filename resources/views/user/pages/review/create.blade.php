@extends('user.layout.default')

@section('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <style>
        .rating-stars ul {
            list-style-type: none;
            padding: 0;
            user-select: none;
            margin-bottom: 0px;
        }

        .rating-stars ul > li.star {
            display: inline-block;
        }

        .rating-stars ul > li.star > i.fa {
            font-size: 1.5em;
            color: #fff;
            text-shadow: 0 0 3px #000;
            cursor: pointer;
        }

        .rating-stars ul > li.star.hover > i.fa {
            color:#FFCC36;
        }

        .rating-stars ul > li.star.selected > i.fa {
            color:#fbaf2a;
            text-shadow: none;
        }

        .rating-stars-btn ul > li.btn.btn-light.selected {
            color:#007bff;
            text-shadow: none;
        }
    </style>
@endsection

{{-- Content --}}
@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow rounded border-0">
                        <div class="body" style="padding: 20px;">
                        
                            <p>{{ trans('main.Thanks for giving your opinion') }}</p>

                            <p>
                                {{ trans('main.Text review form intro') }}&nbsp;
                                <span class='font-weight-600'>{{ $book->seller->name }}&nbsp;</span>
                                {{ trans('main.About the service provided') }}&nbsp;
                                <span class='font-weight-600'>{{ $book->service->name }}</span>
                            </p>
                            
                            <p>{{ trans('main.Text review form recommend') }}</p>
                            <form method="POST" action="{{ route('user.review.store', ['token' => $token]) }}" id="form-rate">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $book->seller_id }}" />
                                <input type="hidden" name="reviewer_id" value="{{ auth()->id() }}" />
                                <input type="hidden" name="book_id" value="{{ $book->id }}" />

                                {{-- rating stars --}}
                                <div class="rating-stars">
                                    <ul id="stars-rate">
                                        <li class="star" title="Poor" data-value="1">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" title="Fair" data-value="2">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" title="Good" data-value="3">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" title="Excellent" data-value="4">
                                            <i class="fa fa-star"></i>
                                        </li>
                                        <li class="star" title="WOW!!!" data-value="5">
                                            <i class="fa fa-star"></i>
                                        </li>
                                    </ul>

                                    <input type="hidden" name="rate" />
                                </div>
                                {{--./ rating stars --}}
                                <br /><br />

                                {{-- comments --}}
                                <p>@lang('main.Evaluation reasons review form')</p>
                                <textarea name="review" rows="10" class="form-control" required
                                    placeholder="@lang('main.Write your reasons text')">{{ old('review') }}</textarea>
                                {{--./ comments --}}
                                <br />

                                <button id="btn-submit" type="submit" class="btn btn-primary float-right mb-4 ml-md-2">@lang('main.Send your opinion button')</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('partial-scripts')
<script>
    $(function(){
        $('.rating-stars li').on('mouseover', function() {
            var onStar = parseInt($(this).data('value'), 10);

            $(this).parent().children('li.star').each(function(e){
                e < onStar ? $(this).addClass('hover') : $(this).removeClass('hover');
            }).on('mouseout', function(){
				$(this).parent().children('li.star').each(function(e){
					$(this).removeClass('hover');
				});
			});;
        });

        $('.rating-stars li').on('click', function() {
            var onStar = parseInt($(this).data('value'), 10);

            var btnStars = $(this).parent().children('li.btn.btn-light');
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < btnStars.length; i++) {
                $(btnStars[i]).removeClass('selected');
            }
            $(btnStars[onStar-1]).addClass('selected');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
        });

        $('#form-rate').on('submit', function(event) {
            $('#btn-submit').attr('disabled', 'disabled');
            var ratingValue = parseInt($('#stars-rate li.selected').last().data('value'), 10);
            ratingValue = isNaN(ratingValue) ? 0 : ratingValue;            
            $('input[name=rate]').val(ratingValue);
        });
    });
</script>
@endsection