<?php

use classes\DbConnector;
use classes\User;

require 'classes/DbConnector.php'; // Adjust the path to your DbConnector class file
require 'classes/User.php'; // Adjust the path to your User class file

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: admin_login.php?status=1"); // Missing required fields
        exit();
    }

    $db = DbConnector::getConnection();
    $user = new User("", "", "", "", 0, "", $email, "", $password, $password, "");

    $authenticatedUser = $user->authenticate($db, $email, $password);

    if ($authenticatedUser) {
        $_SESSION['user'] = $authenticatedUser;
        $db = null; // Close the database connection
        header("Location: admin_dashboard.php"); // Redirect to a dashboard or homepage
        exit();
    } else {
        $db = null; // Close the database connection
        header("Location: admin_login.php?status=2"); // Authentication failed
        exit();
    }
} else {
    header("Location: admin_login.php?status=0"); // No form submission
    exit();
}
?>
