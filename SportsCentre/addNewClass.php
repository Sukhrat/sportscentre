<?php
    session_start();

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }
    
    echo '<a style="margin-bottom: 30px; margin-top: 30px;"class="btn btn-primary" href="loggedinpage.php" role="button">Back to my Profile</a>';

    if(array_key_exists("id", $_SESSION)) {


        include("connection.php");

        //Getting data about current user
            $current_user_query = "SELECT * from `users` WHERE ID = ".mysqli_real_escape_string($link, $_SESSION['id']);

            $current_user_result = mysqli_query($link, $current_user_query);

            $currentUser = mysqli_fetch_array($current_user_result);

        if (array_key_exists("enroll", $_POST)) {

            if ($_POST['placesAvailable'] == 0) {
              echo '
                <div class="alert alert-danger" role="alert">
                  There are no places available. Try another one.
                </div>
              ';
            }
            else {
              
              $user_sections_query = "SELECT * FROM `user_classes` RIGHT OUTER JOIN `classes` ON `classid` = `ID` WHERE `classid` = ".$_POST['ID'].' AND `userid` = '.$_SESSION['id'];

              $result1 = mysqli_query($link, $user_sections_query);

              if (mysqli_num_rows($result1) > 0) {
                echo '<div class="alert alert-danger" role="alert">
                        You are already in this class.
                    </div>';

              } else {

              $places_available = $_POST['placesAvailable'] - 1;

              $places_query = "UPDATE `classes` SET placesAvailable = ".$places_available." WHERE ID = ".mysqli_real_escape_string($link, $_POST['ID'])." LIMIT 1";

              $querie = "INSERT INTO `user_classes` (`userid`, `classid`) VALUES (".mysqli_real_escape_string($link, $_SESSION['id']).", ".mysqli_real_escape_string($link, $_POST['ID']).")";

              mysqli_query($link, $querie);
              mysqli_query($link, $places_query); 
              echo '<div class="alert alert-success" role="alert">
                      You have successfully registered for the class. <strong>Good luck!</strong> 
                    </div>';
              }
          }
        }


    $query = "SELECT * from `classes`";


    echo '<div id="accordion" role="tablist" aria-multiselectable="true"> ';

    $result = mysqli_query($link, $query);

    while($row = mysqli_fetch_array($result)) {

        $name = $row["name"];

        $start_time = $row["begTime"];

        $end_time = $row["endTime"];

        $price = $row["price"];

        $places = $row["placesAvailable"];

        $id = $row["ID"];

        
        echo '

            <div  class="card">
                <div id = "section" class="card-header" role="tab" id="headingOne">
                    <h5 class="mb-0">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            '.$name.'
                        </a>
                    </h5>
                </div>

                <div id="collapse" class="collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="card-block">
                    '.$name.'
                        <p> <strong> Time: </strong>'.$start_time.' - '.$end_time.' </p>
                        <p> <strong> Price: </strong>'.$price.' </p>
                        <p> <strong> Places available: </strong>'.$places.' </p>
                        <form action="" method = "post">
                            <input type="hidden" name="ID" value="'.$row["ID"].'" />
                            <input type="hidden" name="placesAvailable" value="'.$row["placesAvailable"].'" />
                            <input class="btn btn-success" type="submit" name = "enroll" value = "Enroll" >
                        </form>
                    </div>
                </div>
            </div>

        ';

    }
    echo '</div>';
    
}

    include("header.php");

    

?>


<?php include("footer.php");