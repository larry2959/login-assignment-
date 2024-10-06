<?php
// public/register.php
require '../config/config.php';
require '../src/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        echo 'Please fill in all fields.';
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format.';
        exit;
    }

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Store in the database
    $db = new Database();
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $db->query($sql);
    
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($stmt->execute()) {
        echo 'User registered successfully.';
    } else {
        echo 'Something went wrong.';
    }
}
