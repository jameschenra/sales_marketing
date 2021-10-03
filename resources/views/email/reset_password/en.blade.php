@extends('email.layout-en')

@section('email-content')

  <p>We received a request to set a new password. To reset your password click on the button:</p><br><br>

  <p><a href="{{ $reset_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Reset your password</button>
    </a></p><br><br>

  <p>If you didn't request a password change, ignore this email.</p>

@stop