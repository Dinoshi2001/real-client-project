<?php
// Database connection settings
$host = "localhost";   // Replace with your database host
$dbname = "project02";  // Replace with your database name
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data and sanitize inputs
    $name = $conn->real_escape_string(trim($_POST["name"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $message = $conn->real_escape_string(trim($_POST["message"]));

    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Insert form data into the database
        $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($conn->query($sql) === TRUE) {
            echo "Message sent successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
