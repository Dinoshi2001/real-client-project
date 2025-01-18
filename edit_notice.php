<?php
session_start(); // Start the session

require 'dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Collect form data
    $noticeId = $_POST['noticeId'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $target_dir = "uploads/";
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $target_file = $target_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $stmt = $pdo->prepare("SELECT image FROM notices WHERE id = :id");
            $stmt->execute([':id' => $noticeId]);
            $notice = $stmt->fetch(PDO::FETCH_ASSOC);
            $image_path = $notice['image'];
        }
    }

    // Update data in the database using prepared statements
    $sql = "UPDATE notices SET title = :title, description = :description, image = :image WHERE id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':image' => $image_path,
            ':id' => $noticeId,
        ]);
        header("Location: notices.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
