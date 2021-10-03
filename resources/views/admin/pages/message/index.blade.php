@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.User') @lang('main.List')</h3>
                <button onclick="openMessageModal()" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Message')</button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Email') }}</th>
                        <th>{{ trans('main.Phone') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $value)
                        <tr>
                            <td>
                                <label><input type="checkbox" class="user_selected" name="user_selected[]" value="{{ $value->email }}"></label>
                            </td>
                            <td>{{ $value->name }}</td>
                            <td>{{ $value->email }}</td>
                            <td>{{ $value->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $users->links() }}</div>
            <div class="clearfix"></div>
        </div>

    </div>

    <form class="form-horizontal form-bordered form-row-stripped" role="form" method="POST"
        action="{{ route('admin.message.send', $type) }}">
        @csrf

        <div class="modal fade" id="message-modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body">
                        <input type="hidden" id="user_emails" name="user_emails">
                        <div class="form-group">
                            <label for="comment">Message:</label>
                            <textarea class="form-control form_area" rows="5" name="allmessage" id="message" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="usermessage" name="usermessage">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" id="send" name="send" class="btn btn-primary" value="send">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>

                </div>

            </div>

        </div>
    </form>
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script>
    function openMessageModal() {
        var userMails = $(".user_selected:checked").map(function ()
            {return this.value;}
        ).get().join(",");

        $('#user_emails').val(userMails);

        $('#message-modal').modal('show');
    }
</script>
@endsection
