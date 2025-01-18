<?php
session_start(); // Start the session

require 'dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Collect form data
    $petId = $_POST['petId'];
    $petType = $_POST['petType'];
    $petName = $_POST['petName'];
    $petAge = $_POST['petAge'];
    $petDescription = $_POST['petDescription'];
    $petPrice = $_POST['petPrice'];

    // Handle file uploads
    $target_dir = "uploads/";
    $photo_paths = [];

    if (!empty($_FILES['petPhotos']['name'][0])) {
        foreach ($_FILES['petPhotos']['name'] as $key => $photo_name) {
            $target_file = $target_dir . basename($photo_name);
            if (move_uploaded_file($_FILES['petPhotos']['tmp_name'][$key], $target_file)) {
                $photo_paths[] = $target_file;
            }
        }
        $petPhotos = implode(",", $photo_paths);
    } else {
        $stmt = $pdo->prepare("SELECT pet_photos FROM seller_pets WHERE id = :id");
        $stmt->execute([':id' => $petId]);
        $pet = $stmt->fetch(PDO::FETCH_ASSOC);
        $petPhotos = $pet['pet_photos'];
    }

    // Update data in the database using prepared statements
    $sql = "UPDATE seller_pets SET pet_type = :pet_type, pet_name = :pet_name, pet_age = :pet_age, pet_description = :pet_description, pet_price = :pet_price, pet_photos = :pet_photos WHERE id = :id";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pet_type' => $petType,
            ':pet_name' => $petName,
            ':pet_age' => $petAge,
            ':pet_description' => $petDescription,
            ':pet_price' => $petPrice,
            ':pet_photos' => $petPhotos,
            ':id' => $petId,
        ]);
        header("Location: add_pet.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
