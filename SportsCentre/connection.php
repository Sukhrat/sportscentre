<?php 
     $link = mysqli_connect("localhost", "root", "", "sport_centre");
        
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
?>