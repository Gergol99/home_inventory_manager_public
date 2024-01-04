<?php

namespace App\_utils;

require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail {
    public function send($to, $from, $subject, $message) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = "smtp-relay.brevo.com";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // empty by privacy reasons
            $mail->Username = "";
            $mail->Password = "";
    
            $mail->setFrom($from, '');
            $mail->addAddress($to);
    
            $mail->CharSet = "UTF-8";
            $mail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    
            $mail->Body = $message;
    
            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}