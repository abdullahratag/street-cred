<?php

$password = "marc1234";

$password_hashed = password_hash($password, PASSWORD_DEFAULT);

echo $password_hashed;

?>