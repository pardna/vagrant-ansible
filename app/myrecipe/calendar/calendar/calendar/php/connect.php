<?php

// Make a MySQL Connection
$host="localhost";
$user="username";
$password="password";
$db = "database";

$link = mysqli_connect($host, $user, $password);
mysqli_select_db($link, $db) or die(mysql_error());

?>
