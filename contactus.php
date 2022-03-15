<?php
require_once 'php/global.php';
$meta = json('meta');
?>

<!DOCTYPE html>
<html>
    <head>
        <?php globalLinks(); ?>
        <?php source('contactus.css'); ?>
        <title>Ateneo Musicians' Pool - Contact Us</title>
    <head>

    <body>
    <?php createHeader('Contact Us'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
        <div class="contact">
            <h1><i class="fa fa-envelope"></i> Contact Form </h1>
        </div>

        <h4 id="text"> If you have any concerns, requests, or messages, please fill up the following: </h4>
        <p id="subtext">or email us at<a href="mailto:amp.ls@obf.ateneo.edu" target="_blank"> amp.ls@obf.ateneo.edu</a></p>
        <?php include 'mail.php' ?>

        <form method="POST">
            <div class="label">
                <p> Email Address: <font color="red">* </font>
                </p>
            </div>

            <div class="box">
                <input type="email" placeholder="juanadelacruz@gmail.com" id="email" name="email"><br><br>
            </div>

            <div class="label">
                <p> Name: <font color="red">* </font> &nbsp <span id="subp">First Name, Last Name</span></p>
            </div>

            <div class="box">
                <input type="text" placeholder="Juana dela Cruz" id="name" name="name"><br><br>
            </div>

            <div class="label">
                <p> Email Subject: <font color="red">* </font> &nbsp <span id="subp"></p>
            </div>

            <div class="box">
                <input type="text" placeholder="Please Add Email Subject" id="subject" name="subject"><br><br>
            </div>

            <div class="label">
                <p> Concerns/Requests/Messages: <font color="red">*</font></p>
            </div>

            <div class="box">
                <textarea id="message" name="message" placeholder="Please input your concerns/requests/messages here"></textarea><br><br>
            </div>

            <center><input type="submit" value="Submit" name="submit" id="submit" onclick="notif"><br><br></center>
        </form>
    </body>
</html>
