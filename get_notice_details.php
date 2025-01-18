<?php
session_start(); // Start the session

require 'dbConnector.php';

if (isset($_GET['id'])) {
    $noticeId = $_GET['id'];

    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Fetch notice details from the database
    $stmt = $pdo->prepare("SELECT * FROM notices WHERE id = :id");
    $stmt->execute([':id' => $noticeId]);
    $notice = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($notice);
}
?>
