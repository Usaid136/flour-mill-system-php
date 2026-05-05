<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "flour_mill_system";

// Connecting DB
$conn = mysqli_connect($host,$user,$pass,$db);

// Print Error
if(!$conn){
    echo "Connection Failed";
}