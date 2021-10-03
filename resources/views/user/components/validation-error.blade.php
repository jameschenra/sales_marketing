@error($field)
    <span class="invalid-feedback {{ $custom_control ?? 'd-block' }}" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror