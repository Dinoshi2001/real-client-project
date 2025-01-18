<?php
session_start(); // Start the session

require 'dbConnector.php';

if (isset($_GET['id'])) {
    $petId = $_GET['id'];

    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Fetch pet details from the database
    $stmt = $pdo->prepare("SELECT * FROM seller_pets WHERE id = :id");
    $stmt->execute([':id' => $petId]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($pet);
}
?>
