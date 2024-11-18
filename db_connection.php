<?php 
$localhost = 'localhost';

$root = 'root';

$password = 'Tescode@123';

$db_con = 'banking_app';

$conn = mysqli_connect($localhost, $root, $password,$db_con);

if(!$conn){

    die("database connection error");

}
?>