<?php
session_start();
include("connection.php");
$user_id = $_SESSION("user_id");
// print_r($_FILES);

$name = $_FILES["picture"]["name"];
$extension = pathinfo($name, PATHINFO_EXTENSiON);
$tmp_name =  $_FILES["picture"]["tmp_name"];
$fileError = $_FILES["picture"]["error"];

$permanentDestination = 'profilepicture/' . md5(time()) . ".$extension";

if(move_uploaded_file($tmp_name,$permanentDestination)) {
    $sql = "UPDATE users SET profilepicture='$permanentDestination' WHERE user_id='$user_id'";
    $result = mysqli_query($link, $sql);
    if(!$result) {
        echo "<div class='alert alert-danger'>Unable to update profile picture (SQL error)</div>";
    }
}
else{
    echo "<div class='alert alert-danger'>Unable to move profile picture to destination</div>";
}

if($fileError>0) {
    echo "<div class='alert alert-danger'>File error. Error code: $fileError</div>";
}

?>