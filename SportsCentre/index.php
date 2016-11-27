<?php

    session_start();
    include("header.php");
    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        session_unset();
        setcookie(session_id(), "", time() - 2592000, "/");
        $_COOKIE["id"] = "";  
        session_destroy();
        $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        header("Location: $url");
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.'loggedinpage.php';
        header("Location: $url");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
       include("connection.php");

        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `users` (`firstname`, `lastname`, `email`, `phone`,  `password`) VALUES (
                        '".mysqli_real_escape_string($link, $_POST['firstname'])."',
                        '".mysqli_real_escape_string($link, $_POST['lastname'])."',
                        '".mysqli_real_escape_string($link, $_POST['email'])."',
                        '".mysqli_real_escape_string($link, $_POST['phone'])."', 
                        '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        echo '
                            <div class="alert alert-success" role="alert">
                                <strong>Congratulations!</strong> You successfully signed up. <br> Now please sign in using your email and password. 
                            </div>
                        ';

                        

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if ($_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 
                            $url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.'loggedinpage.php';
                            header("Location: $url");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else {
                        
                        $error = "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }



?>




    
    
    <div class = container>
        <div id = "error"  > <?php if ($error != "") {
            echo '<div class="alert alert-danger">'.$error.'</div>';
        } ?> </div>
        <header class="row spacing">
            <ul class="nav nav-tabs" id ="myTab">
                <li class="nav-item">
                    <a class="nav-link active toggleForms" data-toggle="tab" role="tab">Sign Up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link toggleForms" data-toggle="tab" role="tab">Sign In</a>
                </li>
                
            </ul>
        </header>
        <div class="row spacing">
            <form method = "post" id = "signUpForm">
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
                <input class="form-control" type = "password" name = "password" placeholder = "Password">
            
            <fieldset class="form-group">
                <input class="btn btn-success toggleForms" type="submit" name = "submit" value = "Sign up">
            </fieldset>

        </form>

        <form method = "post" id = "logInForm">
            <fieldset class="form-group">
                <input class="form-control" type = "email" name = "email" placeholder = "Your email">
            </fieldset>
                <input class="form-control" type = "password" name = "password" placeholder = "Password">
            <fieldset class="form-group">
                <div class="checkbox"><label for="">
                <input type = "checkbox" name = "stayLoggedIn" value = 1> Stay logged in
                </label></div>
                <input type="hidden" name = "signUp" value = 0>
            </fieldset>
            <fieldset class = "form-group">
                <input class="btn btn-success" type="submit" name = "submit" value = "Sign in">
            </fieldset>

        </form>
        </div>
</div>

<?php include("footer.php") ?>


