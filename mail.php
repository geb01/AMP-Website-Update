<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';

$error = '';
$name = '';
$email = '';
$subject = '';
$message = '';

function clean_text($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

if (isset($_POST["submit"])) {
    if (empty($_POST["name"])) {
        $error .= '<div class = "label"><p>Please Enter Full Name.</p></div>';
    } else {
        $name = clean_text($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $error .= '<div class = "label"><p>Only letters and white spaces allowed.</p></div>';
        }
    }
    if (empty($_POST["email"])) {
        $error .= '<div class = "label"><p>Please Enter Email.</p></div>';
    } else {
        $email = clean_text($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= '<div class = "label"><p>Invalid Email. </p></div>';
        }
    }
    if (empty($_POST["subject"])) {
        $error .= '<div class = "label"><p>Email Subject is required.</p></div>';
    } else {
        $subject = clean_text($_POST["subject"]);
    }

    if (empty($_POST["message"])) {
        $error .= '<div class = "label"><p>Message is required.</p></div>';
    } else {
        $message = clean_text($_POST["message"]);
    }
}

    if ($error != ''){
        $mail = new PHPMailer;

        $mail->SMTPDebug = 0;                               
        
        $mail->isSMTP();
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->Username = '';//username
        $mail->Password = ''; // password
        $mail->Port = 465;
        
        $mail->CharSet="iso-8859-1";
        $mail->SMTPAuth = true;                               
        
        
        $mail->From = '$email';
        $mail->FromName = '$name';
        $mail->addAddress('amp.ls@obf.ateneo.edu');
        
        $mail->isHTML(true);                                  
        
        $mail->Subject = $subject;
        $mail->Body    = '
            Email: '.$email.' <br>
            Name: '.$name.' <br>
            Message: '.$message.' <br>
         ';
        
        if(!$mail->send()) {
            echo $error;
        } 
        
        else {
            echo '';
        }
    }
?>

