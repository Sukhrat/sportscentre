<?php 
    session_start();

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if(array_key_exists("id", $_SESSION)) {
        if (array_key_exists("back", $_POST)) {
            $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
            header("Location: $url");
        }
        echo '
        <form action="" method = "post">
            <input name= "back" type = "submit" style="margin-bottom: 30px; margin-top: 30px;"class="btn btn-primary"  role="button" value = "Back to Profile">
                
           
        </form>';


        include("connection.php");

        $query = "SELECT * from `users` WHERE ID = ".mysqli_real_escape_string($link, $_SESSION['id']);

        $result = mysqli_query($link, $query);

        $currentUser = mysqli_fetch_array($result);

        $userID = $currentUser["id"];

        $currentClass = $_GET['id'];

        $coach_query = "SELECT * FROM `coaches` WHERE section_id = ".$currentClass;

        $coach_result = mysqli_query($link, $coach_query);

        $coach = mysqli_fetch_array($coach_result);


        echo '
            <center>
            <div style= "align:center; width: 300px;"class="card">
                <h2> Coach </h2>
                <img style = "width: 300px;" class="card-img-top" src="'.$coach["photo"].'" alt="Card image cap">
                <div class="card-block">
                    <h3 class = "card-text">'.$coach["fname"].' '.$coach["lname"].' </h3>
                    <p class="card-text"> <strong> Experience: </strong>'.$coach["experience"].' years</p>
                </div>
            </div>
            </center>
        ';
?>
    
<?php

        

        $classmatesQuery = "SELECT * FROM `user_classes` LEFT OUTER JOIN `users` ON `userid` = `id` WHERE `classid` =  ". $currentClass;
        
        $classmate_result = mysqli_query($link, $classmatesQuery);

        echo '<div class="list-group">';

        while($row = mysqli_fetch_array($classmate_result)) {

            $firstname = $row["firstname"];

            $lastname = $row["lastname"];

            $phone = $row["phone"];

            $email = $row["email"];

            $id = $row["id"];


            if ($id == $_SESSION['id']) {
                echo '

                    <a href="#" class="list-group-item list-group-item-action">
                        <h5 class="list-group-item-heading">'.$firstname.' '.$lastname.
                        
                        
                        
                        ' (you)</h5>
                        <p class="list-group-item-text">
                        '.'<strong>Phone: </strong>'.$phone.'
                        </p>
                         <p class="list-group-item-text">
                        '.'<strong>Email: </strong>'.$email.'
                        </p>
                    </a>

                ';
            } else {
                echo '

                    <a href="#" class="list-group-item list-group-item-action">
                        <h5 class="list-group-item-heading">'.$firstname.' '.$lastname.
                        
                        
                        
                        ' </h5>
                        <p class="list-group-item-text">
                        '.'<strong>Phone: </strong>'.$phone.'
                        </p>
                         <p class="list-group-item-text">
                        '.'<strong>Email: </strong>'.$email.'
                        </p>
                    </a>

                ';
            }
        }

        echo '</div>';

    }



    include('header.php');


 ?>


 
  

<?php 
    include('footer.php');
?>