<?php
session_start(); // Start the session

require 'dbConnector.php';

// Check if the notice ID is provided
if (isset($_GET['id'])) {
    $noticeId = $_GET['id'];

    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Delete notice from database
    $sql = "DELETE FROM notices WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $noticeId]);

    header("Location: notices.php");
    exit();
}
?>
