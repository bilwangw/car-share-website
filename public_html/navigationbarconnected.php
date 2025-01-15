<?php
    $user_id = $_SESSION['user_id'];

    // get username
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($link,$sql);
    $count = mysqli_num_rows($result);

    if($count == 1) {
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        $username = $row["username"];

    }else{
        echo "<div class='alert alert-danger'>Error retrieving username from database<div/>";
    }

?>

<!--Navigation Bar-->  
<nav role="navigation" class="navbar navbar-custom navbar-fixed-top">

  <div class="container-fluid">

      <div class="navbar-header">

          <a class="navbar-brand">Car Share</a>
          <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>

          </button>
      </div>
      <div class="navbar-collapse collapse" id="navbarCollapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Search</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="#">Help</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="mainpageloggedin.php">My Trips</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
              <li><a href="#"><?php echo $username?></a></li>
            <li><a href="index.php?logout=1" data-toggle="modal">Logout</a></li>
          </ul>

      </div>
  </div>

</nav>