<?php
use PHPMailer\PHPMailer\PHPMailer;

// src/Auth.php

class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function sendVerificationCode($email) {
        $code = rand(1000, 9999); // Generate a 4-digit random code

        // Insert code into the database
        $sql = "INSERT INTO email_verifications (email, code) VALUES (:email, :code)";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        // Use PHPMailer to send email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Your Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: <b>' . $code . '</b>';

        return $mail->send();
    }

    public function verifyCode($email, $inputCode) {
        $sql = "SELECT * FROM email_verifications WHERE email = :email AND code = :code";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $inputCode);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
