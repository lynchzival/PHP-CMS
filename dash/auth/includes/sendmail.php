<?php

require('vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

function sendmail($sendto, $subject, $content){

    global $mail;

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUREMAIL@GMAIL.COM'; // SMTP username
        $mail->Password = 'YOURPASSWORD'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom('YOUREMAIL@GMAIL.COM', 'VisionWorld'); // email and name
        
        $mail->addAddress($sendto);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        $mail->Body = $content;
    
        $mail->send();
        return "Message has been sent to your address.";

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        session_destroy();
        exit;
    }
}

?>