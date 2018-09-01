<?php

$server     = "localhost";
$database   = "social";
$dbUsername = "root";
$dbPassword = "";
$mysqli     = new mysqli($server, $dbUsername, $dbPassword, $database);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }



