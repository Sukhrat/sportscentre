<?php
    session_start();
    include("connection.php");
    if(array_key_exists("delete", $_POST)) {
        session_destroy();

        $delete_query = "DELETE FROM `users` WHERE id=".$_SESSION['id'];


        mysqli_query($link, $delete_query);

        $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        header("Location: $url");
    }

   
    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
        
        echo "<p>Logged In! <a href='?logout=1'>Log out</a></p>";

        
        
    } else {
        
        header("Location: index.php");
        
    }

    include("header.php");

?>

<div class="container-fluid"  id = "navigation">
        <nav class="navbar navbar-light bg-faded navbar-fixed-top">
        <a class="navbar-brand" href="#">SportsCentre</a>
    
    <div class="form-inline float-xs-right">

        <a href='index.php/?logout=1' ><button class="btn btn-outline-success" type="submit">Logout</button> </a>

    </div>

    </div>
<h4> Personal account </h4>

<hr>
<div class="personalInfo">
  <p>Name: <?php $query = "SELECT `firstname`, `lastname` from `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

        $row = mysqli_fetch_array(mysqli_query($link, $query));

        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        echo $firstname." ".$lastname;  ?> </p>
  <p>Phone: <?php $query = "SELECT `phone` from `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

        $row = mysqli_fetch_array(mysqli_query($link, $query));

        $phone = $row["phone"];
        
        echo $phone ;  ?> </p>
<?php 
        //Getting data about current user
    $current_user_query = "SELECT * from `users` WHERE ID = ".mysqli_real_escape_string($link, $_SESSION['id']);

    $current_user_result = mysqli_query($link, $current_user_query);

    $currentUser = mysqli_fetch_array($current_user_result);

    
?>
    <form method = "post">
        <input  type="submit" name = "delete" value="Delete My Profile">
    </form>
</div>
<hr>




<a style="margin-bottom: 30px; margin-top: 30px;"class="btn btn-primary" href="addNewClass.php" role="button">Change Class</a>

<br>
<?php 
    if (array_key_exists("id", $_SESSION)) {

      $class_query = "SELECT * FROM `user_classes` RIGHT OUTER JOIN `classes` ON `classid` = `ID` WHERE `userid` = ".mysqli_real_escape_string($link, $_SESSION['id']);
      $class_result = mysqli_query($link, $class_query);
      
      //$sectionID = $class_row["classid"];
      if ($class_result != null) {


        echo '
            <h4> Enrolled Classes </h4>
                <div class="card">
        ';

        while($class_row = mysqli_fetch_array($class_result)) {

            $classid = $class_row["classid"];

            $name = $class_row["name"];

            $start_time = $class_row["begTime"];

            $end_time = $class_row["endTime"];

            $price = $class_row["price"];

            $places = $class_row["placesAvailable"];

            $coach_query = "SELECT * FROM `coaches` WHERE section_id = ".$classid;
            $coach_row = mysqli_fetch_array(mysqli_query($link, $coach_query));

            $coach_name = $coach_row["fname"]." ".$coach_row["lname"];
            $coach_experience = $coach_row["experience"]." years";


            echo '
                <div style = "margin-bottom: 10px">
                <div class="card-block">
                    <h4 class="card-title">'.$name.'</h4>
                    <h6 class="card-subtitle text-muted"> <p>'.$start_time.'-'.$end_time.' </h6>
                </div>
                <div class="card-block">
                    <p class="card-text">
                    
                        <strong>Price: </strong>'.$price.' KZT 
                    
                    </p>
                    <p class="card-text">
                    
                        <strong>Places:  </strong>'.$places.'  
                    
                    </p>
                    <a style="margin-bottom: 10px; margin-top: 10px;"class="btn btn-primary" href="classmates.php/?id='.$classid.'" role="button">Classmates</a>
                    <br>
                    <form method = "post">
                        <input type="hidden" name="ID" value="'.$classid.'" />
                        <input type="submit" name = "deleteClass" value="Delete">
                    </form>
                </div>
                </div>
                
                ';
        }
        echo '</div>';
      } else {

      }

      ?>
      <?php

      if (array_key_exists("deleteClass", $_POST)) {

          
            $delete_class_query = "DELETE FROM `user_classes` WHERE classid = ".$_POST['ID']." AND userid =".$_SESSION['id'];
            mysqli_query($link, $delete_class_query);


            $current_class_query = "SELECT * FROM `classes` WHERE ID = ".$_POST['ID'];
            $current_class_result = mysqli_query($link, $current_class_query);
            $currentClass = mysqli_fetch_array($current_class_result);


            $incrPlaces = $currentClass["placesAvailable"] + 1;
            $increase_places_query = "UPDATE `classes` SET placesAvailable = ".$incrPlaces." WHERE id = ".$_POST['ID'];


            mysqli_query($link, $increase_places_query);
                

             header("Refresh:0");
        
      }
    }
 ?>
 <?php 
   include("footer.php");
   

  ?>