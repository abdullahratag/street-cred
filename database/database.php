<?php

$server = "localhost";
$username = "root";
$password = "";
$database_name = "streetcred";

$database_response = "";

try{
   $conn = mysqli_connect($server, $username, $password, $database_name);

    $database_response = "Database connect successful!";
}
catch(mysqli_sql_exception $e){
    $database_response = "Database connect unsuccessful!";

}



?>