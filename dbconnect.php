<?php
    
    define ("DB_USER", "root");
    define ("DB_PASSWORD", "");
    define ("DB_DATABASE", "");
    define ("DB_HOST", "");
    
    @ $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    if($con->connect_error)
        die("Unable to connect to database : ".$con->connect_error);
?>