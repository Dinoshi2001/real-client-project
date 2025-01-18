<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project02";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['customerEmail'];
    $pet_id = $_POST['petID'];
    $pet_name = $_POST['petName'];
    $pet_brand = $_POST['petBrand'];
    $pet_gender = $_POST['petGender'];
    $pet_price = $_POST['petPrice'];

    // Get user_id from users table using email
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Generate a unique trace number
        $trace_no = uniqid();

        // Get the current date and time
        $date_time = date('Y-m-d H:i:s');

        // Insert into transactions table
        $sql = "INSERT INTO transactions (user_id, pet_id, trace_no, date_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $pet_id, $trace_no, $date_time);
        if ($stmt->execute()) {
            // Update the status in pets table
            $sql = "UPDATE pets SET status = 'sold' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $pet_id);
            $stmt->execute();

            sendMail($email, $pet_name, $pet_brand, $pet_gender, $pet_price, $trace_no);
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "User not found!";
    }
}


function sendMail($userMail, $petName, $petBrand, $petGender, $petPrice, $traceNo)
{
    try {
        // echo "sending calling";

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noxdev.testmail@gmail.com';
        $mail->Password = 'dkipbyyodcrnvqkn';

        $mail->setFrom('noxdev.testmail@gmail.com');
        // $mail->addAddress($_POST["email"]);
        $mail->addAddress($userMail);
        $mail->isHTML(true);

        $mail->Subject = "Pet Palooza";
        $mail->Body = "
        <div>
        <h3>Transaction Completed</h3>
        <hr style='margin-bottom:5px;'>
        <ul style='margin-left:10px; margin-bottom:10px;'>
        <li>Pet Name : $petName</li>
        <li>Pet Brand : $petBrand</li>
        <li>Pet Gender : $petGender</li>
        <li>Trace No : $traceNo</li>
        </ul>
        <h3>Total Amount Rs:$petPrice</h3>
        </div>
        ";
        // window.location.href = 'homepage.php';

        $mail->send();
        echo "
        <body style='margin: 0%;  ' >
    <div  style='width: 100vw; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #550A78; color: white;'>
        <h2 style='margin: 0; font-family: 'Franklin Gothic Medium', 'Arial Narrow';'>Transaction Complete</h2>
        <p style='font-family: 'Franklin Gothic Medium', 'Arial Narrow';'>check your mails</p>
        <button class='done' style='background-color: #550A78;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 25px;
        font-size: 1rem;
        transition: background-color 0.3s ease-in-out;
        background-color: white;
        cursor: pointer;
        '>Done</button>
    </div>
    <script>
        document.querySelector('.done').addEventListener('click',(e)=>{
            window.location.href = 'homepage.php';
        })
    </script>
</body>
        ";
    } catch (Exception $e) {
        echo "<script>
                alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
              </script>";
    }
}
