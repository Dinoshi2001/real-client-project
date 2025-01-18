<?php
session_start();
require 'dbConnector.php';

if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['eventId'];
    $seatNumber = $_POST['seatNumber'];
    $userId = $_SESSION['user']['id'];

    // Create a database connection
    $db = new dbConnector();
    $con = $db->getConnection();

    // Check if the user has already registered for the event
    $checkUserRegistrationQuery = "SELECT * FROM event_registrations WHERE event_id = :eventId AND user_id = :userId";
    $checkUserRegistrationStmt = $con->prepare($checkUserRegistrationQuery);
    $checkUserRegistrationStmt->bindParam(':eventId', $eventId);
    $checkUserRegistrationStmt->bindParam(':userId', $userId);
    $checkUserRegistrationStmt->execute();

    if ($checkUserRegistrationStmt->rowCount() > 0) {
        // User already registered for this event
        echo json_encode(['status' => 'error', 'message' => 'You have already registered for this event.']);
        exit();
    }

    // Insert registration into the database
    $insertQuery = "INSERT INTO event_registrations (event_id, seat_number, user_id) VALUES (:eventId, :seatNumber, :userId)";
    $insertStmt = $con->prepare($insertQuery);
    $insertStmt->bindParam(':eventId', $eventId);
    $insertStmt->bindParam(':seatNumber', $seatNumber);
    $insertStmt->bindParam(':userId', $userId);
    
    if ($insertStmt->execute()) {
        // Update available seats
        $updateSeatsQuery = "UPDATE limited_seat_events SET available_seats = available_seats - 1 WHERE id = :eventId";
        $updateSeatsStmt = $con->prepare($updateSeatsQuery);
        $updateSeatsStmt->bindParam(':eventId', $eventId);
        $updateSeatsStmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
