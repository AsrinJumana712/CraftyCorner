<?php
$server="localhost";
$db_username="root";
$db_password="";
$db_name="home_products";

$con=mysqli_connect($server,$db_username,$db_password,$db_name);
if($con->connect_error){
    die("database connection error");
}else {
    echo "";
}
?>