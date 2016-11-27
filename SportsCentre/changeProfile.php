<?php

    session_start();

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    include("header.php");

    echo '<a style="margin-bottom: 30px; margin-top: 30px;"class="btn btn-primary" href="loggedinpage.php" role="button">Back to my Profile</a>';
    if (array_key_exists("id", $_SESSION)) {
        
        include("connection.php");
        
    } else {
        
        header("Location: index.php");
        
    }

    $current_user_query = "SELECT * from `users` WHERE ID = ".mysqli_real_escape_string($link, $_SESSION['id']);

    $current_user_result = mysqli_query($link, $current_user_query);

    $currentUser = mysqli_fetch_array($current_user_result);

    if (array_key_exists("changeForm", $_POST)) {

        echo $currentUser["id"];

        if ($_POST["firstname"]) {

            $update_query = "UPDATE `users` SET firstname=".$_POST["firstname"]." WHERE id=".$currentUser["id"];

            mysqli_query($link, $update_query);

        } 

        if ($_POST["lastname"] != "") {

            $update_query = "UPDATE `users` SET lastname=".$_POST["lastname"]." WHERE id=".$currentUser["id"];

            mysqli_query($link, $update_query);

        }

        if ($_POST["email"] != "") {

            $update_query = "UPDATE `users` SET email=".$_POST["email"]." WHERE id=".$currentUser["id"];

            mysqli_query($link, $update_query);

        }

        if ($_POST["phone"] != "") {

            $update_query = "UPDATE `users` SET lastname=".$_POST["phone"]." WHERE id=".$currentUser["id"];

            mysqli_query($link, $update_query);

        }

    }
?>

<form method = "post" id = "changeForm" name="changeForm">
            <div class="form-group">
                    <input class="form-control" type = "text" name = "firstname" placeholder = "Firstname">

            </div>
            <div class="form-group">
                    
                    <input class="form-control" type = "text" name = "lastname" placeholder = "Lastname">

            </div>
            <fieldset class="form-group">
                <input class="form-control" type = "email" name = "email" placeholder = "Your email">
            </fieldset>
            <div class="form-group">
                    <input 
                                id="phone" 
                                name = "phone"
                                type="phone" 
                                class="form-control"
                                placeholder="Phone"
                                >  
            </div>
            
            <fieldset class="form-group">
                <input class="btn btn-success" type="submit" name = "submit" value = "Change">
            </fieldset>
</form>