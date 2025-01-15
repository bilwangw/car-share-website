

//set map options
var myLatLng = {lat: 51.5, lng: -0.1278};
var mapOptions = {
    center: myLatLng,
    zoom: 7,
}

//create autocomplete objects
var input1 = document.getElementById("departure");
var input2 = document.getElementById("destination");

var options = {
    types: ['(cities)']
}
var autocomplete1 = new google.maps.places.Autocomplete(input1, options);
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);



//initialize: draw map in \googleMap div
function initialize(){
    // create directions renderer object
    var directionsDisplay = new google.maps.DirectionsRenderer();

    //create map
    var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
    //bind direction renderer to map
    directionsDisplay.setMap(map);
}

// On loaded:
google.maps.event.addDomListener(window, 'load', initialize);

// calculate routes when autocompletes are entered
google.maps.event.addListener(autocomplete1, 'place_changed', calcRoute);
google.maps.event.addListener(autocomplete2, 'place_changed', calcRoute);

var directionsService = new google.maps.DirectionsService();

// calc route function
function calcRoute() {
    // create directions renderer object
    var directionsDisplay = new google.maps.DirectionsRenderer();

    //create map
    var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
    //bind direction renderer to map
    directionsDisplay.setMap(map);
    var start = document.getElementById('departure').value;
    var end = document.getElementById('destination').value;
    var request = {
        origin: start,
        destination: end,
        travelMode: 'DRIVING'
    };
    if(start && end) {
        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                directionsDisplay.setDirections(result);
            }
        });
    }
}
