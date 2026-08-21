<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('sendEmailWithPHPMailer')) {
    /**
     * Send an email using PHPMailer.
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body content
     * @return string
     */
    function sendEmailWithPHPMailer($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtpout.secureserver.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port       = env('MAIL_PORT', 587);

            // Sender and recipient
            // $mail->setFrom('admin@sukalelang.id', 'Admin Sukalelang');
            $mail->setFrom(env('MAIL_FROM_ADDRESS','admin@sukalelang.id'), env('MAIL_FROM_NAME','Admin Sukalelang'));
            $mail->addAddress($to);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->SMTPDebug = 2;
            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
            // return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
