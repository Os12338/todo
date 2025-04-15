<?php
// host
// username
// password
// nameOfDatabae

$host = "localhost";
$userName = "root";
$password = "";
$DB = "ToDoApp";

$conn = mysqli_connect($host,$userName,$password,$DB);

if($conn){

}else{
    echo "not connected to database please try again";
}

