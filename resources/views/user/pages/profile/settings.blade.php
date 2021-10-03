{{-- Extends layout --}}
@extends('user.layout.default')

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Settings')</h3>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.settings.update') }}" id="form-setting"
                        class="form-horizontal form-row-seperated" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex">
                                <span class="switch switch-icon">
                                    <label>
                                        <input type="checkbox" name="min_balance_notification"
                                            value="{{ $user->detail->unsubscribe_minimum_credit == 0 ? 0 : 1 }}"
                                            {{ $user->detail->unsubscribe_minimum_credit == '0' ? '' : 'checked' }}
                                            onchange="onChangeOffice(event)" />
                                        <span></span>
                                    </label>
                                </span>
                                <label class="col-form-label ml-4">@lang('main.minimum-balance-notify')</label>
                            </div>

                            @include('user.components.validation-error', ['field' => 'min_balance_notification'])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("input[name='min_balance_notification']").on('change', function(event, state) {
                if ($(this).prop('checked')) {
                    $(this).val(1);
                } else {
                    $(this).val(0);
                }

                $('#form-setting').submit();
            });
        })
    </script>
@endsection
