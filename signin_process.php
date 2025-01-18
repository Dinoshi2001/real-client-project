<?php


use classes\DbConnector;
use classes\User;

require 'classes/DbConnector.php';
require 'classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST["first-name"];
    $last_name = $_POST["last-name"];
    $address = $_POST["address"];
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $nic = $_POST["nic"];
    $email = $_POST["email"];
    $contact_number = $_POST["contact-number"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];
    $gender = $_POST["gender"];

    // Additional validation checks
    if (empty($first_name) || empty($last_name) || empty($address) || empty($dob) || empty($age) || empty($nic) || empty($email) || empty($contact_number) || empty($password) || empty($confirm_password) || empty($gender)) {
        header("Location: sign_in_form.php?status=1"); // Missing required fields
        exit();
    }

    $dob_timestamp = strtotime($dob);
    if ($dob_timestamp === false || $dob_timestamp > time()) {
        header("Location: sign_in_form.php?status=7"); // Future date of birth or invalid date
        exit();
    }

    if (strlen($password) < 8) {
        header("Location: sign_in_form.php?status=4"); // Password too short
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: sign_in_form.php?status=6"); // Passwords do not match
        exit();
    }

    if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[^A-Za-z0-9]/", $password)) {
        header("Location: sign_in_form.php?status=5"); // Password complexity requirements not met
        exit();
    }

    $db = DbConnector::getConnection();
    $user = new User($first_name, $last_name, $address, $dob, $age, $nic, $email, $contact_number, $password, $confirm_password, $gender);

    if ($user->register($db)) {
        $db = null; // Close the database connection
        header("Location: sign_in_form.php?status=2"); // Successful registration
        exit();
    } else {
        $db = null; // Close the database connection
        header("Location: sign_in_form.php?status=3"); // Registration failed
        exit();
    }
} else {
    header("Location: sign_in_form.php?status=0"); // No form submission
    exit();
}
?>