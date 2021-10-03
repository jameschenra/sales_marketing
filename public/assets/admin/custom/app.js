$(function () {
    $("a#btn-delete-item").on('click', function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(function(result) {
            if (result.value) {
                window.location.href = url;
            }
        });
    });
})
