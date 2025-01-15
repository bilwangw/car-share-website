<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Trips</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="styling.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
      
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <style>
        #container{
            margin-top:120px;   
        }

        #notePad, #allNotes, #done, .delete{
            display: none;   
        }

        .buttons{
            margin-bottom: 20px;   
        }

        textarea{
            width: 100%;
            max-width: 100%;
            font-size: 16px;
            line-height: 1.5em;
            border-left-width: 20px;
            border-color: #CA3DD9;
            color: #CA3DD9;
            background-color: #FBEFFF;
            padding: 10px;
              
        }
        
        .noteheader{
            border: 1px solid grey;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 0 10px;
            background: linear-gradient(#FFFFFF,#ECEAE7);
        }
          
        .text{
            font-size: 20px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
          
        .timetext{
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .notes{
            margin-bottom: 100px;
        }

        .modal{
            z-index: 20;
        }

        .modal-backdrop{
            z-index: 10;
        }

        #googleMap{
        }

        .trip{
          border: 1px solid grey;
          padding: 10px;
          border-radius: 10px;
          margin: 10px auto;
          background: linear-gradient(#00fff7,#fb00ff);
        }

        .trips{
          margin: 50px;
        }
        
        .journey {
          font-size: 15px;
        }

      </style>
      <script async
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCf4PMG6hcEJeMnCKwZ7F-k8gn3MpABDSQ&&loading=async&libraries=places">
    </script>
  </head>
  <body>
    <!--Navigation Bar-->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
      
          <div class="container-fluid">
            
              <div class="navbar-header">
              
                  <a class="navbar-brand">Car Sharing</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                  <ul class="nav navbar-nav">
                    <li><a href="index.php">Search</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Contact us</a></li>
                      <li class="active"><a href="#">My Trips</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                      <li><a href="#"><?php echo $_SESSION['username']?></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                  </ul>
              
              </div>
          </div>
      
      </nav>
    
<!--Container-->
      <div class="container" id="container">
          <div class="row">
              <div class="col-sm-8 col-sa-offset-2">
                <button type="button" class="btn btn-lg green" data-toggle="modal" data-target="#addtripModal">
                    Add trips
                </button>
              </div>

              <div id="myTrips" class="trips">
                <!--Ajax call to PHP file-->
              </div>
          </div>
      </div>

    <!--Edit trip form-->
      <form method="post" id="edittripform">
        <div class="modal" id="edittripModal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Edit Trip 
                  </h4>
            </div>
            <div class="modal-body">
                  
                  <!--Add trip message from PHP file-->
                <div id="edittripmessage"></div>
                  
                  <div class="form-group">
                      <label for="departure2" class="sr-only">Departure:</label>
                      <input class="form-control" type="text" name="departure2" id="departure2" placeholder="Departure">
                  </div>
                  <div class="form-group">
                      <label for="destination2" class="sr-only">Destination:</label>
                      <input class="form-control" type="text" name="destination2" id="destination2" placeholder="Destination">
                  </div>
                  <div class="form-group">
                      <label for="price2" class="sr-only">price:</label>
                      <input class="form-control" type="number" name="price2" id="price2" placeholder="Price">
                  </div>
                  <div class="form-group">
                      <label for="seatsavailable2" class="sr-only">seatsavailable:</label>
                      <input class="form-control" type="number" name="seatsavailable2" id="seatsavailable2" placeholder="Seats available">
                  </div>
                  <div class="form-group">
                      <label><input type="radio" name="regular2" id="yes2" value="Y">Regular</label>
                      <label><input type="radio" name="regular2" id="no2" value="N">One-off</label>
                  </div>
                  <div class="checkbox checkbox-inline regular2" id="day2">
                    <label><input type="checkbox" name="monday2" id="monday2" value="1">Monday</label>
                    <label><input type="checkbox" name="tuesday2" id="tuesday2" value="1">Tuesday</label>
                    <label><input type="checkbox" name="wednesday2" id="wednesday2" value="1">Wednesday</label>
                    <label><input type="checkbox" name="thursday2" id="thursday2" value="1">Thursday</label>
                    <label><input type="checkbox" name="friday2" id="friday2" value="1">Friday</label>
                    <label><input type="checkbox" name="saturday2" id="saturday2" value="1">Saturday</label>
                    <label><input type="checkbox" name="sunday2" id="sunday2" value="1">Sunday</label>
                  </div>
                  <div class="form-group one-off2" id="date">
                      <label for="date2" class="sr-only">Date:</label>
                      <input class="form-control" 
                      type="date" name="date2" id="date2">
                  </div>
                  <div class="form-group regular2 one-off2" id="time">
                      <label for="time2" class="sr-only">Time:</label>
                      <input class="form-control" type="time" name="time2" id="time2">
                  </div>


              </div>
              <div class="modal-footer">
              <input class="btn green" name="edittrip" type="submit" value="Update Trip">
              <input class="btn btn-danger" name="deleteTrip" value="Delete" id="deleteTrip">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>
              </div>
          </div>
      </div>
      </div>
      </form>


<!--Add trip form-->
<form method="post" id="addtripform">
        <div class="modal" id="addtripModal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    New Trip 
                  </h4>
            </div>
            <div class="modal-body">
                  
                  <!--Add trip message from PHP file-->
                <div id="addtripmessage"></div>

                  <!--Google Map-->
                  <div id="googleMap"></div>
                  
                  <div class="form-group">
                      <label for="departure" class="sr-only">Departure:</label>
                      <input class="form-control" type="text" name="departure" id="departure" placeholder="Departure">
                  </div>
                  <div class="form-group">
                      <label for="destination" class="sr-only">Destination:</label>
                      <input class="form-control" type="text" name="destination" id="destination" placeholder="Destination">
                  </div>
                  <div class="form-group">
                      <label for="price" class="sr-only">price:</label>
                      <input class="form-control" type="number" name="price" id="price" placeholder="Price">
                  </div>
                  <div class="form-group">
                      <label for="seatsavailable" class="sr-only">seatsavailable:</label>
                      <input class="form-control" type="number" name="seatsavailable" id="seatsavailable" placeholder="Seats available">
                  </div>
                  <div class="form-group">
                      <label><input type="radio" name="regular" id="yes" value="Y">Regular</label>
                      <label><input type="radio" name="regular" id="no" value="N">One-off</label>
                  </div>
                  <div class="checkbox checkbox-inline regular" id="day">
                    <label><input type="checkbox" name="monday" id="monday" value="1">Monday</label>
                    <label><input type="checkbox" name="tuesday" id="tuesday" value="1">Tuesday</label>
                    <label><input type="checkbox" name="wednesday" id="wednesday" value="1">Wednesday</label>
                    <label><input type="checkbox" name="thursday" id="thursday" value="1">Thursday</label>
                    <label><input type="checkbox" name="friday" id="friday" value="1">Friday</label>
                    <label><input type="checkbox" name="saturday" id="saturday" value="1">Saturday</label>
                    <label><input type="checkbox" name="sunday" id="sunday" value="1">Sunday</label>
                  </div>
                  <div class="form-group one-off" id="date">
                      <label for="date" class="sr-only">Date:</label>
                      <input class="form-control" 
                      type="date" name="date" id="date">
                  </div>
                  <div class="form-group regular one-off" id="time">
                      <label for="time" class="sr-only">Time:</label>
                      <input class="form-control" type="time" name="time" id="time">
                  </div>


              </div>
              <div class="modal-footer">
                  <input class="btn green" name="createtrip" type="submit" value="Create Trip">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>
              </div>
          </div>
      </div>
      </div>
      </form>

    <!-- Footer-->
      <div class="footer">
          <div class="container">
              <p>bill wang website real Copyright &copy; 2015-<?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>


    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="map.js"></script>  
    <script src="mytrips.js"></script>  
    
    
  </body>
</html>