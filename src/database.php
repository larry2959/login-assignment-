<?php
// src/Database.php

class Database {
    private $host = 'localhost';   // Hostname for XAMPP is usually localhost
    private $user = 'root';        // Default username for XAMPP MySQL is 'root'
    private $pass = '';            // Default password for root in XAMPP is empty
    private $dbname = 'user_management'; // Your database name

    private $dbh;
    private $error;
    
    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        try {
            // Create a new PDO instance with the connection settings
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Handle connection error by storing and echoing the error message
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepare the SQL query using the database handler
    public function query($sql) {
        return $this->dbh->prepare($sql);
    }
}
