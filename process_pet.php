<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'DbConnector.php'; 
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Create a new PDO instance
$db = new DbConnector();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $petType = $_POST['petType'];
    $brand = $_POST['brand'];
    $country = $_POST['country'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Additional validation checks
    if (empty($petType) || empty($brand) || empty($country) || empty($age) || empty($gender) || empty($price) || empty($description)) {
        header("Location: admin_dashboard_add_pets.php?status=1"); // Missing required fields
        exit();
    }

    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            header("Location: admin_dashboard_add_pets.php?status=2"); // File upload error
            exit();
        }
    }

    // Insert pet data into the database
    $stmt = $conn->prepare("INSERT INTO pets (pet_type, brand, country, age, gender, price, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$petType, $brand, $country, $age, $gender, $price, $description, $image])) {
        // Get the pet ID
        $pet_id = $conn->lastInsertId();

        // Notify the first user in the waiting list for the specific pet type and brand and remove them from the list
        $stmt = $conn->prepare("SELECT * FROM waiting_list WHERE pet_type = ? AND pet_brand = ? ORDER BY id ASC LIMIT 1");
        $stmt->execute([$petType, $brand]);
        $waitingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($waitingUser) {
            // Fetch user email from the users table
            $userId = $waitingUser['user_id'];
            $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && isset($user['email'])) {
                // Send email notification to the user
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'noxdev.testmail@gmail.com';
                    $mail->Password = 'dkipbyyodcrnvqkn';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('noxdev.testmail@gmail.com', 'PetPalooza');
                    $mail->addAddress($user['email']);
                    $mail->isHTML(true);

                    $mail->Subject = 'Pet Availability Notification';
                    $mail->Body    = "Dear user,<br><br>We have just added a new pet of brand '$brand' to our shop. You can now check it out on our website.<br><br>Best regards,<br>PetPalooza";

                    $mail->send();

                    // Remove the user from the waiting list
                    $stmt = $conn->prepare("DELETE FROM waiting_list WHERE id = ?");
                    $stmt->execute([$waitingUser['id']]);
                } catch (Exception $e) {
                    // Log email sending error or notify admin
                    error_log('Email not sent: ' . $mail->ErrorInfo);
                }
            } else {
                // Handle case where user email is not found
                error_log('User email not found for user ID: ' . $userId);
            }
        }

        header("Location: admin_dashboard_add_pets.php?status=3");
    } else {
        error_log('Database insert error: ' . $stmt->errorInfo()[2]);
        header("Location: admin_dashboard_add_pets.php?status=4");
    }
}
?>
