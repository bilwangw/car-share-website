<?php
session_start();
include('connection.php');

//logout
include('logout.php');

//remember me
include('remember.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Car Share Website</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="styling.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js"></script>
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCf4PMG6hcEJeMnCKwZ7F-k8gn3MpABDSQ&libraries=places"></script>-->
    <script async type="text/javascript" 
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCf4PMG6hcEJeMnCKwZ7F-k8gn3MpABDSQ&loading=async&libraries=places">
    </script>

  </head>
  <body>
    <!--Navigation Bar-->  
      <?php
          if(isset($_SESSION["user_id"])){
              include("navigationbarconnected.php");
          }else{
              include("navigationbarnotconnected.php");
          }
      ?>
    
    <div class="container-fluid" id = "myContainer">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <h1>Plan your next trip NOW!!!</h1>
              <p>Economical!</p>
              <p>save moners</p>
              <!--Search Form-->
              <form class="form-inline" method="get" id="searchForm">
                <div class = "form-group">
                  <label class="sr-only" for="departure">Departure:</label>
                  <input type="text" placeholder="Departure" name="departure" id="departure">
                </div>
                <div class = "form-group" onsubmit="return false;">
                  <label class="sr-only" for="destination">Destination:</label>
                  <input type="text" placeholder="Destination" name="destination" id="destination">
                </div>
                <input type="submit" value="Search" class="btn btn-lg green">
              </form>

              <!-- Google Map -->
              <div id="googleMap"></div>



              <!-- Sign up button -->
              <?php
                if(!isset($_SESSION["user_id"])){
                  echo "<button class='btn btn-lg green signup' data-toggle='modal' data-target='#signupModal'>Sign up for free!</button>";
                }
              ?>
              <!-- Search results -->
              <div id="searchResults"></div>
            </div>
          </div>

    </div>

    <!--Login form-->    
      <form method="post" id="loginform">
        <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Login: 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Login message from PHP file-->
                  <div id="loginmessage"></div>
                  

                  <div class="form-group">
                      <label for="loginemail" class="sr-only">Email:</label>
                      <input class="form-control" type="email" name="loginemail" id="loginemail" placeholder="Email" maxlength="50">
                  </div>
                  <div class="form-group">
                      <label for="loginpassword" class="sr-only">Password</label>
                      <input class="form-control" type="password" name="loginpassword" id="loginpassword" placeholder="Password" maxlength="30">
                  </div>
                  <div class="checkbox">
                      <label>
                          <input type="checkbox" name="rememberme" id="rememberme">
                        Remember me
                      </label>
                          <a class="pull-right" style="cursor: pointer" data-dismiss="modal" data-target="#forgotpasswordModal" data-toggle="modal">
                      Forgot Password?
                      </a>
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="login" type="submit" value="Login">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="signupModal" data-toggle="modal">
                  Register
                </button>  
              </div>
          </div>
      </div>
      </div>
      </form>

    <!--Sign up form--> 
      <form method="post" id="signupform">
        <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Sign up to use car sharing servicess! 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Sign up message from PHP file-->
                  <div id="signupmessage"></div>
                  
                  <div class="form-group">
                      <label for="username" class="sr-only">Username:</label>
                      <input class="form-control" type="text" name="username" id="username" placeholder="Username" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="firstname" class="sr-only">First name:</label>
                      <input class="form-control" type="text" name="firstname" id="firstname" placeholder="First Name" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="lastname" class="sr-only">Last name:</label>
                      <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Last Name" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="email" class="sr-only">Email:</label>
                      <input class="form-control" type="email" name="email" id="email" placeholder="Email Address" maxlength="50">
                  </div>
                  <div class="form-group">
                      <label for="password" class="sr-only">Choose a password:</label>
                      <input class="form-control" type="password" name="password" id="password" placeholder="Choose a password" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="password2" class="sr-only">Confirm password</label>
                      <input class="form-control" type="password" name="password2" id="password2" placeholder="Confirm password" maxlength="30">
                  </div>
                  <div class="form-group">
                      <label for="phonenumber" class="sr-only">Phone Number</label>
                      <input class="form-control" type="text" name="phonenumber" id="phonenumber" placeholder="Phone Number" maxlength="30">
                  </div>          
                  <div class="form-group">
                      <label><input type="radio" name="gender" id="male" value="male">Male</label>
                      <label><input type="radio" name="gender" id="female" value="female">Female</label>
                  </div>    
                  <div class="form=group">
                    <label for="moreinformation">Comments</label>
                    <textarea name="moreinformation" id="moreinformation" class="form-control" maxlength="300"></textarea>
                  </div>      
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="signup" type="submit" value="Sign up">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>
              </div>
          </div>
      </div>
      </div>
      </form>

    <!--Forgot password form-->
      <form method="post" id="forgotpasswordform">
        <div class="modal" id="forgotpasswordModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Forgot Password? Enter your email address: 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--forgot password message from PHP file-->
                  <div id="forgotpasswordmessage"></div>
                  

                  <div class="form-group">
                      <label for="forgotemail" class="sr-only">Email:</label>
                      <input class="form-control" type="email" name="forgotemail" id="forgotemail" placeholder="Email" maxlength="50">
                  </div>
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="forgotpassword" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="signupModal" data-toggle="modal">
                  Register
                </button>  
              </div>
          </div>
      </div>
      </div>
      </form>
    <!-- Footer-->
      <div class="footer">
          <div class="container">
              <p>bwangbwangbwang Copyright &copy; 2015-<?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>


    <script src="javascript.js"></script>
    <script src="map.js"></script>
  </body>
</html>