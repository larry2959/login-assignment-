<?php
//Creating constants
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "oop_form";
  $port = 3307;

  try {
    // Create the connection
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully :-)";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?> 