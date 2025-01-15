//create a geocoder object
getTrips();
var geocoder = new google.maps.Geocoder();
var departureLongitude;
var departureLatitude;
var destinationLatitude;
var destinationLongitude;

//copy the entirety of map.js so mytrips doesnt bug out
//set map options
var myLatLng = {lat: 51.5, lng: -0.1278};
var mapOptions = {
    center: myLatLng,
    zoom: 7,
}
var map;

//create autocomplete objects
var input1 = document.getElementById("departure");
var input2 = document.getElementById("destination");
var input3 = document.getElementById("departure2");
var input4 = document.getElementById("destination2");

var options = {
    types: ['(cities)']
}
var autocomplete1 = new google.maps.places.Autocomplete(input1, options);
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);
var autocomplete3 = new google.maps.places.Autocomplete(input3, options);
var autocomplete4 = new google.maps.places.Autocomplete(input4, options);


//initialize: draw map in \googleMap div
function initialize(){
    // create directions renderer object
    var directionsDisplay = new google.maps.DirectionsRenderer();

    //create map
    map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
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




$(function(){
    //define variables
    
    // fix map
    $("#addtripModal").on('show.bs.modal', function() {
        google.maps.event.trigger(map, "resize");
    });
    
});

// hide checkboxes before selecting options

$('.regular').hide();
$('.one-off').hide();
$('.regular2').hide();
$('.one-off2').hide();
// document.getElementById("day").hidden = true;
// document.getElementById("time").hidden = true;
// document.getElementById("date").hidden = true;
// document.getElementById("day2").hidden = true;
// document.getElementById("time2").hidden = true;
// document.getElementById("date2").hidden = true;

var myRadio = $("input[name='regular']");

myRadio.click(function(){
    if($(this).is(':checked')) {
        if($(this).val()== "Y"){
            // document.getElementById("day").hidden = false;
            // document.getElementById("time").hidden = false;
            // document.getElementById("date").hidden = true;
            $('.one-off').hide();
            $('.regular').show();
        }
        else{
            // document.getElementById("day").hidden = true;
            // document.getElementById("time").hidden = false;
            // document.getElementById("date").hidden = false;
            $('.regular').hide();
            $('.one-off').show();
        }
    }
});

// for edit trip
var myRadio = $("input[name='regular2']");

myRadio.click(function(){
    if($(this).is(':checked')) {
        if($(this).val()== "Y"){
            // document.getElementById("day2").hidden = false;
            // document.getElementById("time2").hidden = false;
            // document.getElementById("date2").hidden = true;
            $('.one-off2').hide();
            $('.regular2').show();
        }
        else{
            // document.getElementById("day2").hidden = true;
            // document.getElementById("time2").hidden = false;
            // document.getElementById("date2").hidden = false;
            $('.regular2').hide();
            $('.one-off2').show();
        }
    }
});

// calendar
$("#date").datepicker({
    numberOfMonths: 1,
    showAnim: "fadein",
    dateFormat: "D d M, yy",
    minDate: +1,
    maxDate: "+12M"
});

var data;
// create trip
$("#addtripform").submit(function(event){
    event.preventDefault();
    data = $(this).serializeArray();
    getAddTripDepartureCoordinates();
});


//def funcs
function getAddTripDepartureCoordinates() {
    geocoder.geocode(
        {
        'address': document.getElementById("departure").value
        },
        function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                departureLongitude = results[0].geometry.location.lng();
                departureLatitude = results[0].geometry.location.lat();
                data.push({name:'departureLongitude', value: departureLongitude});
                data.push({name:'departureLatitude', value: departureLatitude});
                getAddTripDestinationCoordinates();
            }
            else{
                getAddTripDestinationCoordinates();
            }
        }
    );
}

function getAddTripDestinationCoordinates() {
    geocoder.geocode(
        {
        'address': document.getElementById("destination").value
        },
        function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                destinationLongitude = results[0].geometry.location.lng();
                destinationLatitude = results[0].geometry.location.lat();
                data.push({name:'destinationLongitude', value: destinationLongitude});
                data.push({name:'destinationLatitude', value: destinationLatitude});
                submitAddTripRequest();
            }
            else{
                submitAddTripRequest();
            }
        }
    );
}

function submitAddTripRequest() {
    //send ajax call
    $.ajax({
        url: "addtrips.php",
        type: "POST",
        data: data,
        success: function(returnData){
            if(returnData) {
                $("#addtripmessage").html(returnData);
            }
            else{
                //hide modal
                $("#addtripModal").modal('hide');
                //reset form
                $("#addtripform")[0].reset();
                //load trips
                getTrips();
            }
        },
        error: function(){
            $("#addtripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
        }
    
    });
}

// get trips to display
function getTrips() {
//send ajax call
    $.ajax({
        url: "gettrips.php",
        success: function(returnData){
            $("#myTrips").html(returnData);
        },
        error: function(){
            $("#myTrips").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
        }

    });
}

var trip;

function formatModal() {
    $('#departure2').val(trip['departure']);
    $('#destination2').val(trip['destination']);
    $('#price2').val(trip['price']);
    $('#seatsavailable2').val(trip['seatsavailable']);
    if(trip['regular']=="Y") {
        $('#yes2').prop('checked', true);
        $('#monday2').prop('checked', false);
        $('#tuesday2').prop('checked', false);
        $('#wednesday2').prop('checked', false);
        $('#thursday2').prop('checked', false);
        $('#friday2').prop('checked', false);
        $('#saturday2').prop('checked', false);
        $('#sunday2').prop('checked', false);
        if(trip['monday'] == "1") {$('#monday2').prop('checked', true);}
        if(trip['tuesday'] == "1") {$('#tuesday2').prop('checked', true);}
        if(trip['wednesday'] == "1") {$('#wednesday2').prop('checked', true);}
        if(trip['thursday'] == "1") {$('#thursday2').prop('checked', true);}
        if(trip['friday'] == "1") {$('#friday2').prop('checked', true);}
        if(trip['saturday'] == "1") {$('#saturday2').prop('checked', true);}
        if(trip['sunday'] == "1") {$('#sunday').prop('checked', true);}
        $('input[name="time2"]').val(trip["time"]);
        $('.one-off2').hide();
        $('.regular2').show();
    } else{
        $('#no2').prop('checked', true);
        $('input[name="time2"]').val(trip["time"]);
        $('input[name="date2"]').val(trip["date"]);
        $('.one-off2').show();
        $('.regular2').hide();

    }
}

function getEditTripDepartureCoordinates() {
    geocoder.geocode(
        {
        'address': document.getElementById("departure2").value
        },
        function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                departureLongitude = results[0].geometry.location.lng();
                departureLatitude = results[0].geometry.location.lat();
                data.push({name:'departureLongitude', value: departureLongitude});
                data.push({name:'departureLatitude', value: departureLatitude});
                getEditTripDestinationCoordinates();
            }
            else{
                getEditTripDestinationCoordinates();
            }
        }
    );
}

function getEditTripDestinationCoordinates() {
    geocoder.geocode(
        {
        'address': document.getElementById("destination2").value
        },
        function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                destinationLongitude = results[0].geometry.location.lng();
                destinationLatitude = results[0].geometry.location.lat();
                data.push({name:'destinationLongitude', value: destinationLongitude});
                data.push({name:'destinationLatitude', value: destinationLatitude});
                submitEditTripRequest();
            }
            else{
                submitEditTripRequest();
            }
        }
    );
}

function submitEditTripRequest() {
    //send ajax call
    $.ajax({
        url: "updatetrips.php",
        type: "POST",
        data: data,
        success: function(returnData){
            if(returnData) {
                $("#edittripmessage").html(returnData);
            }
            else{
                //hide modal
                $("#edittripModal").modal('hide');
                //reset form
                $("#edittripform")[0].reset();
                //load trips
                getTrips();
            }
        },
        error: function(){
            $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
        }
    
    });
}

// edit button clicked
$("#edittripModal").on('show.bs.modal',function(event){
    $("#edittripmessage").empty();
    var invoker = $(event.relatedTarget);
    // ajax call to get trip details
    $.ajax({
        url: "gettripdetails.php",
        method: "POST",
        data: {trip_id: invoker.data('trip_id')},
        success: function(returnData){

            if(returnData) {
                if(returnData == "error") {
                    $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");

                }else {
                    trip = JSON.parse(returnData);
                    formatModal();
                }
            }
        },
        error: function(){
            $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
        }

    });

    $('#edittripform').submit(function(event) {
        $("#edittripmessage").empty();
        event.preventDefault();
        data = $(this).serializeArray();
        data.push({name:'trip_id', value: invoker.data('trip_id')});
        getEditTripDepartureCoordinates();
    });

    $('#deleteTrip').click(function(){
        $.ajax({
            url: "deletetrips.php",
            method: "POST",
            data: {trip_id: invoker.data('trip_id')},
            success: function(returnData){
    
                if(returnData) {
                    if(returnData == "error") {
                        $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
    
                    }else {
                        $("#edittripModal").modal('hide');
                        getTrips();
                    }
                }
            },
            error: function(){
                $("#edittripmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            }
    
        });
    });
});