<?php
session_start();
require_once 'classes/DbConnector.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    try {
        $con = classes\DbConnector::getConnection();
        $query = "SELECT id, first_name, last_name, address, email, contact_number FROM users WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching user data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No user ID in session']);
}
?>
