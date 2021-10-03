<div class="modal fade" id="add-favourite-modal" tabindex="-1" role="dialog" aria-labelledby="favouriteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="favouriteModalLabel">@lang('main.Add to my favorites')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body pt-12">
                <div class="form-group d-flex">
                    <div class="symbol symbol-50 symbol-lg-120">
                        <img id="favourite-modal-photo" class="border-round" src="" alt="image">
                    </div>
                    <div class="ml-4">
                        <h4 class="favourite-modal-name"></h4>
                        <h5 class="favourite-modal-category text-secondary"></h5>
                    </div>
                </div>
                <div class="form-group">
                    <label>@lang('main.Additional notes to my favorites')</label>
                    <textarea id="favourite-modal-note" rows="6" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="favourite_id" id="favourite-id" value="" />
                <input type="hidden" name="favourite_type" id="favourite-type" value="" />
                <button type="button" class="btn btn-primary font-weight-bold" onclick="onSubmitFavourite()">@lang('main.Save to My favorites')</button>
            </div>
        </div>
    </div>
</div>