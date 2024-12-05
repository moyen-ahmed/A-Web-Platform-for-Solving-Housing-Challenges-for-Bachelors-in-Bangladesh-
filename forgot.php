<?php

// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'tolet_for_bachelor';

$con = new mysqli($server, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Function to send email using PHPMailer
function sendMail($email, $resetToken) {
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;                                //Enable SMTP authentication
        $mail->Username   = 'estyakahmefmoyen@gmail.com';                     //SMTP username
        $mail->Password   = 'estyak123';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('estyakahmefmoyen@gmail.com', 'TOLET-For_Bavhelor');
        $mail->addAddress('$email');     //Add a recipient
     
    
     // Content
     $mail->isHTML(true);
     $mail->Subject = 'Password Reset Link from TOLET-For_Bachelor';
     $mail->Body = "
         <p>We received a request to reset your password. Please use the link below to reset your password:</p>
         <p><a href='http://localhost/demo/updatepass.php?email=$email&resettoken=$resetToken'>Reset Password</a></p>
         <p>If you did not request this, please ignore this email.</p>
     ";

     $mail->send();
     return true;
 } catch (Exception $e) {
     error_log("Mail Error: " . $mail->ErrorInfo);
     return false;
 }
}

// Handle reset link request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send-reset-link'])) {
 $email = $con->real_escape_string($_POST['email']);

 // Validate email
 if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
     // Check if the email exists in the database
     $query = "SELECT * FROM `registration` WHERE `email` = '$email'";
     $result = $con->query($query);

     if ($result && $result->num_rows === 1) {
         // Generate a unique reset token
         $reset_token = bin2hex(random_bytes(16));

         // Set timezone and get the current time
         date_default_timezone_set('Asia/Dhaka');
         $date = date("Y-m-d H:i:s");

         // Update the database with the reset token and expiry time
         $update_query = "UPDATE `registration` 
                          SET `resettoken` = '$reset_token', 
                              `resettokenex` = DATE_ADD('$date', INTERVAL 1 HOUR) 
                          WHERE `email` = '$email'";

         if ($con->query($update_query)) {
             // Send the reset email
             if (sendMail($email, $reset_token)) {
                 echo "A password reset link has been sent to $email.";
             } else {
                 echo "Failed to send the reset email. Please try again later.";
             }
         } else {
             echo "Failed to update the reset token in the database.";
         }
     } else {
         echo "No user found with this email address.";
     }
 } else {
     echo "Invalid email address.";
 }
} else {
 echo "Invalid request. Please submit the form.";
}
?>