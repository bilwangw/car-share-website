<?php
    session_start();
    include("connection.php");

    $departure = $_POST["departure"];
    $destination = $_POST["destination"];
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

    
    if($errors) {
        $resultMessage = "<div class='alert alert-danger'>$errors</div>";
        echo $resultMessage;
        exit;
    }
    //search radius in miles
    $searchRadius = 10;

    // calculate search tolerance
    // Departure longitude

    $departureLngOutOfRange = false;
    $destinationLngOutOfRange = false;

    $deltaLongitudeDeparture = abs($searchRadius*360/(24901*cos(deg2rad($departureLongitude))));

    $minLongitudeDeparture = $departureLongitude - abs($deltaLongitudeDeparture);
    if($minLongitudeDeparture < -180) {
        $departureLngOutOfRange = true;
        $minLongitudeDeparture += 360;
    }

    $maxLongitudeDeparture = $departureLongitude + $deltaLongitudeDeparture;
    if($maxLongitudeDeparture > 180) {
        $departureLngOutOfRange = true;
        $maxLongitudeDeparture -= 360;
    }
    // destination longitude
    $deltaLongitudeDestination = abs($searchRadius*360/(24901*cos(deg2rad($destinationLongitude))));

    $minLongitudeDestination = $destinationLongitude - $deltaLongitudeDestination;
    if($minLongitudeDestination < -180) {
        $destinationLngOutOfRange = true;
        $minLongitudeDestination += 360;
    }

    $maxLongitudeDestination = $destinationLongitude + $deltaLongitudeDestination;
    if($maxLongitudeDestination > 180) {
        $destinationLngOutOfRange = true;
        $maxLongitudeDestination -= 360;
    }

    // departure latitude
    $deltaLatitudeDeparture = $searchRadius*360/12430;

    $minLatitudeDeparture = $departureLatitude - $deltaLatitudeDeparture;
    if($minLatitudeDeparture < -90) {
        $minLatitudeDeparture = -90;
    }

    $maxLatitudeDeparture = $departureLatitude + $deltaLatitudeDeparture;
    if($maxLatitudeDeparture > 90) {
        $maxLatitudeDeparture = 90;
    }

    // destination latitude 
    $deltaLatitudeDestination = $searchRadius*360/12430;

    $minLatitudeDestination = $destinationLatitude - $deltaLatitudeDestination;
    if($minLatitudeDestination < -90) {
        $minLatitudeDestination = -90;
    }

    $maxLatitudeDestination = $destinationLatitude + $deltaLatitudeDestination;
    if($maxLatitudeDestination > 90) {
        $maxLatitudeDestination = 90;
    }

    // sql query
    
    $sql = "SELECT * FROM carsharetrips WHERE";
    if($departureLngOutOfRange) {
        $sql .= "((departureLongitude > $minLongitudeDeparture) OR (departureLongitude < $maxLongitudeDeparture))";
    } else {
        $sql .= "(departureLongitude BETWEEN $minLongitudeDeparture AND $maxLongitudeDeparture)";
    }
    $sql .= " AND (departureLatitude BETWEEN $minLatitudeDeparture AND $maxLatitudeDeparture)";

    // destination
    
    if($destinationLngOutOfRange) {
        $sql .= " AND ((destinationLongitude > $minLongitudeDestination) OR (destinationLongitude < $maxLongitudeDestination))";
    } else {
        $sql .= " AND (destinationLongitude BETWEEN $minLongitudeDestination AND $maxLongitudeDestination)";
    }

    $sql .= " AND (destinationLatitude BETWEEN $minLatitudeDestination AND $maxLatitudeDestination)";

    // run query
    $result = mysqli_query($link, $sql);
    if(!$result) {
        echo "ERROR: unable to execute: $sql" . mysqli_error($link);
        exit;
    }
    if(mysqli_num_rows($result) == 0) {
        echo "<div class='alert alert-info'>There are no journeys matching your search</div>";
        exit;
    }

    echo "<div class='alert alert-info'>From $departure to $destination: Closest Journeys</div>";

    echo "<div id='tripResults'>";

    //cycle thru trips
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        //display one off and regu differently
        if($row['regular']=='N') {
            $frequency = "One-off journey.";   
            $time = $row['date'] . " at " . $row['time'] . ".";
            // check the date
            $source = $row['date'];
            $tripDate = DateTime::createFromFormat('D d M, Y', $source);
            $today = date('D d M, Y');
            $todayDate = DateTime::createFromFormat('D d M, Y', $today);
            if($tripDate < $todayDate) {
                continue;
            }

        }
        else {
            $frequency = "Regular.";   
            $array = [];
            if($row['monday'] == '1') {array_push($array, "Mon");}
            if($row['tuesday'] == '1') {array_push($array, "Tue");}
            if($row['wednesday'] == '1') {array_push($array, "Wed");}
            if($row['thursday'] == '1') {array_push($array, "Thu");}
            if($row['friday'] == '1') {array_push($array, "Fri");}
            if($row['saturday'] == '1') {array_push($array, "Sat");}
            if($row['sunday'] == '1') {array_push($array, "Sun");}
            $time = implode("-",$array) . " at " . $row['time'] . ".";
        }
        $departure = $row['departure'];
        $destination = $row['destination'];
        $price = "Price per seat: $" . $row['price'];
        $seatsavailable = $row['seatsavailable'];
        $trip_id = $row['trip_id'];

        //get driver info
        $person_id = $row['user_id'];
        $sql2 = "SELECT * FROM users WHERE user_id='$person_id' LIMIT 1";
        $result2 = mysqli_query($link, $sql2);
        if(!$result2) {
            echo "ERROR: unable to execute: $sql" . mysqli_error($link);
        }
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $firstname = $row2['first_name'];
        $gender = $row2['gender'];
        $moreinfo = $row2['moreinformation'];
        $pfp = $row2['profilepicture'];
        if($_SESSION['user_id']) {
            $phonenumber = $row2['phonenumber'];
        } else{
            $phonenumber = "PLEASE LOGIN";
        }

        echo "
        <h4 class='row tripDisplay'>
            <div class='col-sm-2'>
                <div class='driver'>$firstname</div>
                <div><img class='pfp' src='$picture'></div>
            </div>
            <div class='col-sm-8'>
                <div><span>Departure:</span> $departure </div>
                <div><span>Destination:</span> $destination </div>
                <div>$time</div>
                <div></div>
            </div>
            <div class='col-sm-2'>
                <div>$price</div>
                <div>$seatsavailable seats left</div>
                <div></div>

            </div>
        </h4>

        <div class='moreinfo'>
            <div>Sex: $gender</div>
            <div>☎: $phonenumber</div>
            <div class='moreinfo'>About driver: $moreinfo</div>

        </div>
        ";
    }

    echo "</div>";
?>