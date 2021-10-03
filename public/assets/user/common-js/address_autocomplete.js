
function getAutocompleteAddress(id) {
    new google.maps.places.Autocomplete(document.getElementById(id), {
        types: ['(cities)']
    });
}