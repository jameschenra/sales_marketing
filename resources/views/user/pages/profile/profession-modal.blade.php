<div class="modal fade" id="professionModal" tabindex="-1" role="dialog" aria-labelledby="professionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="professionModalLabel">@lang('main.Enter profession popup')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-profession">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('main.Select category professions popup')</label>
                                <select class="form-control" id="select-profession-category" size="10">
                                    <option class="select-placeholder" value="" selected>@lang('main.Select category of Professions')</option>
                                    @foreach ($professions as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('main.Select profession popup')</label>
                                <select class="form-control" id="select-profession" size="10">
                                    <option class="select-placeholder" value="">@lang('main.Select a profession')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">@lang('main.Cancel profession popup')</button>
                <button id="btn-add-prof" type="button" class="btn btn-primary font-weight-bold">@lang('main.Add profession popup')</button>
            </div>
        </div>
    </div>
</div>