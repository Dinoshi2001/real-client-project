<?php
session_start(); // Start the session

require 'dbConnector.php';

// Check if the pet ID is provided
if (isset($_GET['id'])) {
    $petId = $_GET['id'];

    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Delete pet from database
    $sql = "DELETE FROM seller_pets WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $petId]);

    header("Location: add_pet.php");
    exit();
}
?>
