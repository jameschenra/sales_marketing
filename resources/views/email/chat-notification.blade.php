@extends('email.layout-en')@section('email-content')  <h3 style="color:#ccc; float:left;width: 100%;">Hello {{ $conversation->status->recipient->name }}, </br></br> </h3>  <br/>  <p style="padding:15px 0; font-size:14px;">    <strong>{{ $conversation->sender->name }}</strong> has sent you an email. <a        href="{{ url("messages/{$conversation->conversation_id}") }}" style="text-decoration: underline">Click here</a>    or the button below to view this.  </p>  <p style="padding:15px 0; font-size:14px;">    <a href="{{ url("messages/{$conversation->conversation_id}") }}"       style="background: #2b8b3a; color: #fff; padding: 8px 20px; ">View</a>  </p>@stop