@if (session('message', false))
    <div class="alert alert-{{ session('message')['type'] }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">{{ trans('main.Close') }}</span>
        </button>
        {{ session('message')['msg'] }}
    </div>
@endif