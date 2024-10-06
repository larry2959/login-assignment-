<?php
session_start();
include 'pdo.php';
require 'mailling.php';

// Handle user registration
if (isset($_POST['reg-user'])) {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo ("Passwords do not match!");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

    try {
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        $stmt->execute();

        header('location: login.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

//Handle login 1st factor
if (isset($_POST['login-user'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch the user by email
    $stmt = $conn->prepare('SELECT * FROM users WHERE Email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['Password'])) {
        // Step 2: Check if 2FA is enabled
        if ($user['2FAStatus']) {
            // Generate a random 6-digit verification code
            $verificationCode = rand(100000, 999999);
            $expiry = new DateTime('+10 minutes'); // Code expires in 10 minutes

            // Update the user record with the verification code and expiry time
            $stmt = $conn->prepare('UPDATE users SET VerificationCode = ?, CodeExpiry = ? WHERE UserID = ?');
            $stmt->execute([$verificationCode, $expiry->format('Y-m-d H:i:s'), $user['UserID']]);

            // Send the verification code to the user's email
            $subject = "OTP FOR THEE SYSTEM!";
            $message = "
            Your OTP needed for login is <br>
            <h3>".$verificationCode."</h3>
            Kindly use it within 10 minutes for successful login";
            
            if (sendMail($user['Email'], $subject, $message)) {
                
                // Store user ID in session and redirect to the 2FA verification page
                $_SESSION['user_id'] = $user['UserID'];
                header('Location: 2FA.php');
                exit;
            } else {
                echo "Failed to send the 2FA code.";
            }
        } else {
            // If 2FA is not enabled, log the user in directly
            session_start();
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['logged_in'] = true;
            echo "Login successful without 2FA!";
        }
    } else {
        echo "Invalid username or password.";
    }
}

//handle 2nd factor
elseif (isset($_POST['2fa-user'])) {
    
    // Fetch the user ID from the session
    $userId = $_SESSION['user_id'];
    $enteredCode = $_POST['code'];

    // Fetch the stored verification code and expiry time for the user
    $stmt = $conn->prepare('SELECT VerificationCode, CodeExpiry FROM users WHERE UserID = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the entered code matches and if it's still valid (not expired)
        $currentDateTime = new DateTime();
        $codeExpiry = new DateTime($user['CodeExpiry']);

        if ($user['VerificationCode'] === $enteredCode && $currentDateTime < $codeExpiry) {
            // Code is valid, complete login
            $_SESSION['logged_in'] = true;
            // Redirect to user dashboard or homepage
            header('Location: admin/index.php');
            exit;
        } else {
            // Invalid code or expired
            echo "Invalid or expired 2FA code.";
        }
    } else {
        echo "User not found.";
    }
}

$conn = null;
?>
