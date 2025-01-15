<?php
    session_start();
    include("connection.php");

    $user_id = $_SESSION["user_id"];

    //def error msgs
    $missingDeparture = '<p><strong>Please enter your departure</strong></p>';
    $invalidDeparture = '<p><strong>Invalid departure</strong></p>';
    $missingDestination = '<p><strong>Please enter your destination</strong></p>';
    $invalidDestination = '<p><strong>Invalid destination</strong></p>';
    $missingPrice = '<p><strong>Please enter your price</strong></p>';
    $invalidPrice = '<p><strong>Please enter a valid price</strong></p>';
    $missingSeatsavailable = '<p><strong>Please enter a seats available</strong></p>';
    $invalidSeatsavailable = '<p><strong>Please enter a valid seats</strong></p>';
    $missingFrequency = '<p><strong>Please select a frequency</strong></p>';
    $missingDate = '<p><strong>Please select a date</strong></p>';
    $missingTime = '<p><strong>Please select a time</strong></p>';


    // get inputs
    $departure = $_POST["departure"];
    $destination = $_POST["destination"];
    $price = $_POST["price"];
    $seatsavailable = $_POST["seatsavailable"];
    $regular = $_POST["regular"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $monday = $_POST["monday"];
    $tuesday = $_POST["tuesday"];
    $wednesday = $_POST["wednesday"];
    $thursday = $_POST["thursday"];
    $friday = $_POST["friday"];
    $saturday = $_POST["saturday"];
    $sunday = $_POST["sunday"];

    // error handling
    if(empty($departure)) {
        $errors .= $missingDeparture;
    }
    else{
        if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"])) {
            $errors .= $invalidDeparture;
        }
        else {
            $departureLatitude = $_POST["departureLatitude"];
            $departureLongitude = $_POST["departureLongitude"];
            $departure = filter_var($departure, FILTER_SANITIZE_STRING);
        }
    }

    if(empty($destination)) {
        $errors .= $missingDestination;
    }
    else{
        if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"])) {
            $errors .= $invalidDestination;
        }
        else {
            $destinationLatitude = $_POST["destinationLatitude"];
            $destinationLongitude = $_POST["destinationLongitude"];
            $destination = filter_var($destination, FILTER_SANITIZE_STRING);
        }
    }

    if(empty($price)) {
        $errors .= $missingPrice;
    }
    elseif(preg_match('/\D/',$price)) {
        $errors .= $invalidPrice;
    }
    else{
        $price = filter_var($price, FILTER_SANITIZE_STRING);
    }

    if(empty($seatsavailable)) {
        $errors .= $missingSeatsavailable;
    }
    elseif(preg_match('/\D/',$seatsavailable)) {
        $errors .= $invalidSeatsavailable;
    }
    else{
        $seatsavailable = filter_var($seatsavailable, FILTER_SANITIZE_STRING);
    }

    if(empty($regular)) {
        $errors .= $missingFrequency;
    }
    elseif($regular == "Y") {
        if(empty($monday) && empty($tuesday) && empty($wednesday) && empty($thursday) && empty($friday) && empty($saturday) && empty($sunday)) {
            $errors .= $missingDate;
        }
        if(empty($time)) {
            $errors .= $missingTime;
        }
    }
    else{
        if(empty($date)) {
            $errors .= $missingDate;
        }
        if(empty($time)) {
            $errors .= $missingTime;
        }
    }


    if($errors) {
        $resultMessage = "<div class='alert alert-danger'>$errors</div>";
        echo $resultMessage;
    }
    else{
        // no errors, prepare query
        $departure = mysqli_real_escape_string($link, $departure);
        $destination = mysqli_real_escape_string($link, $destination);
        $tblName = 'carsharetrips';
        if($regular == "Y") {
            // reg trip
            $sql = "INSERT INTO $tblName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `time`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`) VALUES ('$user_id','$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$time','$monday','$tuesday','$wednesday','$thursday','$friday','$saturday','$sunday')";
        }
        else {
            // one off trip
            $sql = "INSERT INTO $tblName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `time`, `date`) VALUES ('$user_id','$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$time','$date')";
            
        }

        $results = mysqli_query($link, $sql);
        if(!results) {
            echo "<div class='alert alert-danger'>Error with SQL query</div>";
        }
    }

?>
