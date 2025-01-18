<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';



try {
    // if (isset($_POST["send"])) {

    echo "sending calling";

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'noxdev.testmail@gmail.com';
    $mail->Password = 'dkipbyyodcrnvqkn';

    $mail->setFrom('noxdev.testmail@gmail.com');
    // $mail->addAddress($_POST["email"]);
    $mail->addAddress('raweensmrnyk@gmail.com');
    $mail->isHTML(true);

    $mail->Subject = "Pet Palooza";
    $mail->Body = "
    <div>
    <h3>Transaction Completed</h3>
    <hr style='margin-bottom:5px;'>
    <ul style='margin-left:10px; margin-bottom:10px;'>
    <li>Pet Name</li>
    <li>Pet Age</li>
    <li>Pet Bread</li>
    </ul>
    <h3>Total Amount 17,000Rs</h3>
    </div>
    ";

    $mail->send();
    echo "Message Sent Successfully";
    echo "
        <script>
        window.location.href = 'home.php'
        </script>
        ";
    // }
} catch (Exception $e) {
    echo "Error";
}
