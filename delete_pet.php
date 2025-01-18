<?php
require_once 'classes/DbConnector.php'; // Adjust the path as per your directory structure
require_once 'classes/Pet.php'; // Adjust the path as per your directory structure

use classes\DbConnector;

if (isset($_GET['id'])) {
    $petId = $_GET['id'];

    try {
        $con = DbConnector::getConnection();
        $stmt = $con->prepare("DELETE FROM pets WHERE id = :id");
        $stmt->bindParam(':id', $petId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            header("Location: admin_dashboard_view_pets.php?message=Pet deleted successfully");
            exit();
        } else {
            header("Location: admin_dashboard_view_pets.php?message=Failed to delete pet");
            exit();
        }
    } catch (PDOException $exc) {
        header("Location: admin_dashboard_view_pets.php?message=Error: " . $exc->getMessage());
        exit();
    }
} else {
    header("Location: admin_dashboard_view_pets.php?message=Invalid request");
    exit();
}
?>
