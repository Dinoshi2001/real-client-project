<?php
session_start();
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user']['id'];

if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    $stmt = $pdo->prepare("SELECT * FROM lostfind_pets WHERE pet_id = ? AND user_id = ?");
    $stmt->execute([$pet_id, $user_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pet) {
        echo json_encode(['success' => true, 'data' => $pet]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pet not found or you do not have permission to edit this pet.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Pet ID not provided.']);
}
?>