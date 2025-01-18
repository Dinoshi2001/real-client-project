<?php
session_start();
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

$errors = [];

if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    $stmt = $pdo->prepare("DELETE FROM lostfind_pets WHERE pet_id = ? AND user_id = ?");
    $stmt->execute([$pet_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Pet details deleted successfully.";
    } else {
        $_SESSION['errors'] = "Failed to delete pet details.";
    }
}

header('Location: manage_lostfound_pets.php');
exit();
?>