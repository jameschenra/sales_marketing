<script>
    function openProfFavModal(event, favouriteId, name) {
        openFavouriteModal($(event.target), favouriteId, name, "{{ \App\Models\Favourite::TYPE_PROFESSIONAL }}",
            '.professional-category-container', '.service-img-wrapper');
    }

    function openServiceFavModal(event, favouriteId, name) {
        openFavouriteModal($(event.target), favouriteId, name, "{{ \App\Models\Favourite::TYPE_SERVICE }}",
            '.service-category-container', '.service-img-wrapper');
    }

    function openFavouriteModal(rowObj, favouriteId, name, type, categoryContainer, imgContainer) {
        var name = decodeURI(name);
        var parentRow = rowObj.closest('.detail-row-container');
        var catItems = parentRow.find(categoryContainer + ' a');
        var categories = "";
        if (catItems.length >= 2) {
            catItems.each(function(index, item) {
                if (index + 1 === catItems.length) {
                    let tempStr = $(this).text().trim();
                    categories += tempStr.substr(0, 3) + "...";
                } else {
                    categories += $(this).text().trim() + " - ";
                }
            });
        } else {
            categories = catItems.text().trim();
        }

        const imageSrc = parentRow.find(imgContainer + ' img').attr('src');

        $('.favourite-modal-name').html(name);
        $('.favourite-modal-category').html(categories);
        $('#favourite-modal-photo').attr('src', imageSrc);

        $('#add-favourite-modal #favourite-id').val(favouriteId);
        $('#add-favourite-modal #favourite-type').val(type);

        $('#add-favourite-modal').modal('show');
    }

    function onSubmitFavourite() {
        const favourite_id = $('#favourite-id').val();
        const type = $('#favourite-type').val();
        const note = $('#favourite-modal-note').val();

        ajax_post(
            "/favorites/add",
            {
                favourite_id,
                type,
                note
            },
            function (result) {
                var msg = '';
                if (type == "{{ \App\Models\Favourite::TYPE_PROFESSIONAL }}") {
                    msg = "{{ trans('main.Professional added to your favorites') }}";
                } else {
                    msg = "{{ trans('main.Service added to your favorites') }}";
                }
                notifyAlert(msg, 'success');

                $('#add-favourite-modal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 3000);
            }
        );
    }

    function deleteFavouriteModal(favouriteId, type) {
        Swal.fire({
            text: "{{ trans('main.Do you want to remove from favorites') }}",
            showCancelButton: true,
            confirmButtonText: "{{ trans('main.Yes') }}",
            cancelButtonText: "{{ trans('main.cancel_pop-up') }}"
        }).then(function(result) {
            if (result.value) {
                ajax_post(
                    "/favorites/delete/" + favouriteId,
                    {},
                    function (result) {
                        var msg = '';
                        if (type == "{{ \App\Models\Favourite::TYPE_PROFESSIONAL }}") {
                            msg = "{{ trans('main.Professional removed to your favorites') }}";
                        } else {
                            msg = "{{ trans('main.Service removed to your favorites') }}";
                        }
                        notifyAlert(msg, 'success');

                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    }
                );
            }
        });
    }
</script>