<?php
// Include required classes
include 'classes/DbConnector.php';
include 'classes/User.php';

use classes\DbConnector;
use classes\User;

// Start the session
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_form.php");
    exit;
}

$user = $_SESSION['user']; // User details from session

if (!isset($user['id'])) {
    die("User ID is not set in session. Please log in again.");
}

// Get the database connection
$conn = DbConnector::getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petType = $_POST['petType'];
    $petBrand = $_POST['petBrand'];

    // Validate and sanitize input
    $petType = htmlspecialchars($petType);
    $petBrand = htmlspecialchars($petBrand);

    try {
        // Insert into the waiting list
        $stmt = $conn->prepare("INSERT INTO waiting_list (user_id, pet_type, pet_brand) VALUES (:user_id, :pet_type, :pet_brand)");
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':pet_type', $petType);
        $stmt->bindParam(':pet_brand', $petBrand);

        if ($stmt->execute()) {
            // Success response
            echo json_encode(['message' => 'Successfully added to the waiting list.']);
        } else {
            // Error response
            echo json_encode(['message' => 'Error adding to the waiting list.']);
        }
    } catch (Exception $e) {
        // Exception response
        echo json_encode(['message' => 'An error occurred: ' . $e->getMessage()]);
    }
    exit;
}
?>
