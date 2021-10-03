var searchSubmitted = false;

var markers = new Array();
var alternateMarkers = [], markersIcon = [];

$(function () {
    getAutocompleteAddress('city-selector');

    initSearchFieldEvents();
});

function initSearchFieldEvents() {
    $('.search-submit').on('click', function (event) {
        searchSubmit(event, this);
    });

    $('select:not(.select-profession)').on('change', formSubmit);

	$('input[type=checkbox]').on('change', formSubmit);

	$("#city-selector").on('change', formSubmit);
}

function searchSubmit(event, searchButtonObj) {
    event.preventDefault();

    // is for blocking double submit
    if (searchSubmitted) {
        return;
    }
    searchSubmitted = true;

    var searchUrl = searchBaseUrl;
    var formData = $(searchButtonObj).parents('form').serializeArray();
    var queryParams = '';
    var filters = {};
    for (let field of formData) {
        filters[field.name] = field.value;
    }

    if (filters.category_name) {
        searchUrl += filters.category_name;

        if (filters.sub_category_name) {
            searchUrl += '/' + filters.sub_category_name;
        }
    }

    for (const key in filters) {
        if (key != 'category_name' && key != 'sub_category_name' && filters[key]) {
            queryParams += key + '=' + filters[key] + '&';
        }
    }
    
    if (queryParams) {
        queryParams = queryParams.slice(0, -1);
        searchUrl += '?' + queryParams;
    }

    var address = $('#city-selector').val();
    if (address != '') {
        new google.maps.Geocoder().geocode({ 'address': address }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var address = results[0].address_components[0].short_name;
                $.get("https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=" + mapAPIKey, function (data) {
                    $("input[name='lat']").val(data.results[0].geometry.location.lat);
                    $("input[name='lng']").val(data.results[0].geometry.location.lng);
                    lat = data.results[0].geometry.location.lat;
                    lng = data.results[0].geometry.location.lng;
                }).then(function () {
                    if (lat && lng) {
                        searchUrl += '&lat=' + lat;
                        searchUrl += '&lng=' + lng;
                    }
                    document.location.replace(searchUrl);
                });

            }
        });
    } else {
        document.location.replace(searchUrl);
    }
}

function formSubmit() {
    setTimeout(() => {
        $('.search-submit').trigger('click');
    }, 200);
}