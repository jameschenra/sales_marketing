$(function () {
    initServiceTable();
});

function initServiceTable() {
    var table = $('#tbl-my-service');

    table.DataTable({
        responsive: true,
        columnDefs: [
            {
                width: '75px',
                targets: 5,
                render: function(data) {
                    var status = {
                        0: {'title': 'Draft', 'class': ' label-light-primary'},
                        1: {'title': 'Published', 'class': ' label-light-success'},
                    };
                    if (typeof status[data] === 'undefined') {
                        return data;
                    }
                    return '<span class="label label-lg font-weight-bold' + status[data].class + ' label-inline">' + status[data].title + '</span>';
                },
            },
        ]
    });
}