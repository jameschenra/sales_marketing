@php
    use App\Models\Service;
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/book/book.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">{{ $service->name }}</h3>
        
        @include('user.pages.book.single-book-item', ['service' => $book->service, 'book' => $book, 'is_detail' => true])

        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
            @include('user.pages.book.online-order-messages', ['book' => $book, 'user_type' => 'buyer'])
        @endif
        <br />
        <div class="mr-5">
            @if (request()->get('from_balance'))
                <a href="{{ route('user.balance.show') }}" class="float-right btn btn-light-primary" style="width: 100px;">@lang('main.Back')</a>
            @else
                <a href="{{ route('user.book') }}" class="float-right btn btn-light-primary" style="width: 100px;">@lang('main.Back')</a>
            @endif
        </div>
    </div>
</section>
@endsection

@section('scripts')
    @include('user.pages.book.online-order-message-js')
@endsection