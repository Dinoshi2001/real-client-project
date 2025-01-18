<?php
session_start();
require_once 'classes/DbConnector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userid'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $nic = $_POST['nic'];
    $gender = $_POST['gender'];

    try {
        $conn = classes\DbConnector::getConnection();

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, contact_number = :contact_number, 
                address = :address, dob = :dob, age = :age, nic = :nic, gender = :gender WHERE id = :userid";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':nic', $nic);
        $stmt->bindParam(':gender', $gender);

        if ($stmt->execute()) {
            // Set success message in session
            $_SESSION['success_message'] = 'Profile updated successfully!';
            header('Location:homepage.php'); // Redirect to the profile page
            exit();
        } else {
            echo "Error updating record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}
?>
