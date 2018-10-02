<?php
// https://github.com/PHPMailer/PHPMailer
// https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps

namespace classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TuaFormaSendMail {

    public function __construct() {       

    }

    public function send() {

        if ( get_option('tua-forma-smtp-enabled') === 'true') { 
            $smtp_enabled = true;
        } else {
            $smtp_enabled = false;
        }
               
        $smtp_protocol = get_option('tua-forma-smtp-protocol');
        $smtp_server = get_option('tua-forma-smtp-server');
        $smtp_port = get_option('tua-forma-smtp-port');
        
        $smtp_username = get_option('tua-forma-smtp-user');
        $smtp_password = get_option('tua-forma-smtp-pass');
        
        $from = get_option('tua-forma-smtp-from');
        $reply_to = get_option('tua-forma-smtp-reply-to');

        $recipients = explode(',',get_option('tua-forma-smtp-recipients'));
        $subject = get_option('tua-forma-subject');

        $body = 'Solo una prueba';


        //Server settings
        $mail = new PHPMailer;                  // Passing `true` enables exceptions
        // $mail = new PHPMailer;
        $mail->isSMTP();                         // Set mailer to use SMTP
        $mail->SMTPDebug = 0;                   //  0 = off (for production use) |  1 = client messages  2 = client and server messages
        error_log($smtp_server);
        $mail->Host = $smtp_server;              // Specify main and backup SMTP servers

        if (get_option('tua-forma-smtp-authentication') === '1')  {
            $mail->SMTPAuth = true;              // Enable SMTP authentication
            $mail->Username = $smtp_username;    // SMTP username
            $mail->Password = $smtp_password;    // SMTP password
        } else {
            $mail->SMTPAuth = false;             // Enable SMTP authentication

        }           

        switch ($smtp_protocol) {
            case('tls'):
                $mail->SMTPSecure = 'tls';
                break;
            case('ssl'):
                $mail->SMTPSecure = 'ssl';
                break;
            default:
                break;
        }
        
        error_log($smtp_port);
        $mail->Port = $smtp_port;                // TCP port to connect to

        //Recipients
        $mail->setFrom($from);
        foreach ($recipients as $to) {
            $to = trim($to);
            error_log($to);
            $mail->addAddress($to); 
        }
        $mail->addReplyTo($reply_to);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
    
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
        //Content
        $mail->isHTML(false);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $body;
            
        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            echo "Message sent!";
            return true;
        }   
    }
}