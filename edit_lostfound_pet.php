<?php
session_start();
require 'dbConnector.php';

// Handle GET request to fetch pet details for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    
    $db = new dbConnector();
    $pdo = $db->getConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM lostfind_pets WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pet) {
        echo json_encode($pet);
    } else {
        echo json_encode(['error' => 'Pet not found.']);
    }
    exit();
}

// Handle POST request to update pet details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_pet_id'])) {
    $pet_id = $_POST['edit_pet_id'];
    $pet_type = $_POST['pet_type'];
    $pet_brand = $_POST['pet_brand'];
    $description = $_POST['description'];
    $imagePath = null;
    $errors = [];

    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Process image upload if selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $uploadPath = 'uploads/';
        $imageName = uniqid() . '_' . $image['name'];
        $imagePath = $uploadPath . $imageName;
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $errors[] = "Failed to upload image.";
        }
    }

    // Prepare SQL query
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE lostfind_pets SET pet_type = ?, pet_brand = ?, description = ?, images = ? WHERE pet_id = ?");
                $stmt->execute([$pet_type, $pet_brand, $description, $imagePath, $pet_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE lostfind_pets SET pet_type = ?, pet_brand = ?, description = ? WHERE pet_id = ?");
                $stmt->execute([$pet_type, $pet_brand, $description, $pet_id]);
            }

            $pdo->commit();

            // Set success message in session
            $_SESSION['success'] = "Pet details updated successfully.";
            echo json_encode(['success' => true, 'pet' => $updatedPetData]);

            // Redirect to manage page
            header('Location: manage_lostfound_pets.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Failed to update pet details.";
            header('Location: manage_lostfound_pets.php');
            exit();
        }
    } else {
        // Set error message in session
        $_SESSION['error'] = "Failed to update pet details: " . implode(', ', $errors);
        header('Location: manage_lostfound_pets.php');
        exit();
    }
}

// If no valid action is taken, redirect to manage page
header('Location: manage_lostfound_pets.php');
exit();
?>
