<?php
$link = mysqli_connect("localhost", "bwangstu_user", "bwang1", "bwangstu_database");
if(mysqli_connect_error()){
    die('ERROR: Unable to connect:' . mysqli_connect_error()); 
    echo "<script>window.alert('Hi!')</script>";
}
    ?>