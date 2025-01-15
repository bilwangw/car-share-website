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
    $trip_id = $_POST["trip_id"];
    $departure = $_POST["departure2"];
    $destination = $_POST["destination2"];
    $price = $_POST["price2"];
    $seatsavailable = $_POST["seatsavailable2"];
    $regular = $_POST["regular2"];
    $date = $_POST["date2"];
    $time = $_POST["time2"];
    $monday = $_POST["monday2"];
    $tuesday = $_POST["tuesday2"];
    $wednesday = $_POST["wednesday2"];
    $thursday = $_POST["thursday2"];
    $friday = $_POST["friday2"];
    $saturday = $_POST["saturday2"];
    $sunday = $_POST["sunday2"];

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
            $sql = "UPDATE $tblName SET `departure` = '$departure', `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude', `destination` = '$destination', `destinationLongitude` = '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price`='$price', `seatsavailable` = '$seatsavailable',`regular`='$regular',`time`='$time',`monday`='$monday',`tuesday`='$tuesday',`wednesday`='$wednesday',`thursday`='$thursday',`friday`='$friday',`saturday`='$saturday',`sunday`='$sunday' WHERE `trip_id` = '$trip_id' LIMIT 1";
        }
        else {
            // one off trip
            $sql = "INSERT INTO $tblName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `time`, `date`) VALUES ('$user_id','$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatsavailable','$regular','$time','$date')";

            $sql = "UPDATE $tblName SET `departure` = '$departure', `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude', `destination` = '$destination', `destinationLongitude` = '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price`='$price', `seatsavailable` = '$seatsavailable',`regular`='$regular',`time`='$time', `date`='$date' WHERE `trip_id` = '$trip_id' LIMIT 1";
            
        }

        $results = mysqli_query($link, $sql);
        if(!results) {
            echo "<div class='alert alert-danger'>Error with SQL query, trip could not be updated</div>";
        }
    }

?>
