var geocoder = new google.maps.Geocoder();


//Ajax Call for the sign up form 
//Once the form is submitted
$("#signupform").submit(function(event){ 
    //prevent default php processing
    event.preventDefault();
    //collect user inputs
    var datatopost = $(this).serializeArray();
//    console.log(datatopost);
    //send them to signup.php using AJAX
    $.ajax({
        url: "signup.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data){
                $("#signupmessage").html(data);
            }
        },
        error: function(){
            $("#signupmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            
        }
    
    });

});

//Ajax Call for the login form
//Once the form is submitted
$("#loginform").submit(function(event){ 
    //prevent default php processing
    event.preventDefault();
    //collect user inputs
    var datatopost = $(this).serializeArray();
//    console.log(datatopost);
    //send them to login.php using AJAX
    $.ajax({
        url: "login.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data == "success"){
                window.location = "mainpageloggedin.php";
            }else{
                $('#loginmessage').html(data);   
            }
        },
        error: function(){
            $("#loginmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            
        }
    
    });

});


//Ajax Call for the forgot password form
//Once the form is submitted
$("#forgotpasswordform").submit(function(event){ 
    //prevent default php processing
    event.preventDefault();
    //collect user inputs
    var datatopost = $(this).serializeArray();
//    console.log(datatopost);
    //send them to signup.php using AJAX
    $.ajax({
        url: "forgot-password.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            
            $('#forgotpasswordmessage').html(data);
        },
        error: function(){
            $("#forgotpasswordmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
            
        }
    
    });

});

var data;
//search form keeps refreshing idk why hopefully its working
$("#searchForm").submit(function(event){
    event.preventDefault();
    data = $(this).serializeArray();
    getSearchTripDepartureCoordinates();
    return false;
});

function getSearchTripDepartureCoordinates() {
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
                getSearchTripDestinationCoordinates();
            }
            else{
                getSearchTripDestinationCoordinates();
            }
        }
    );
}

function getSearchTripDestinationCoordinates() {
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
                submitSearchTripRequest();
            }
            else{
                submitSearchTripRequest();
            }
        }
    );
}



function submitSearchTripRequest() {
    console.log(data);
    //send ajax call
    $.ajax({
        url: "search.php",
        type: "POST",
        data: data,
        success: function(returnData){
            $("#searchResults").html(returnData);
            $("#tripResults").accordion({
                active: false,
                collapsible: true,
                heightStyle: "content",
                icons: false
            });
        },
        error: function(){
            $("#searchResults").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
        }
    
    });
}

