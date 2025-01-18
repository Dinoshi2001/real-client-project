
<?php
use classes\DbConnector;

require_once 'classes/DbConnector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $petId = intval($_POST['petId']);
    $petType = $_POST['petType'];
    $brand = $_POST['brand'];
    $country = $_POST['country'];
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $price = floatval($_POST['price']);
    $description = $_POST['description'];
    
    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $_FILES['image']['tmp_name']; // Get the temporary file path
        $image = file_get_contents($imagePath); // Read the image data
    }

    // Update the database
    try {
        $conn = DbConnector::getConnection();
        
        // Prepare the update query
        $query = "UPDATE pets SET pet_type = ?, brand = ?, country = ?, age = ?, gender = ?, price = ?, description = ?";
        $params = [$petType, $brand, $country, $age, $gender, $price, $description];

        // If a new image is uploaded, include it in the update query and parameters
        if ($image) {
            $query .= ", image = ?";
            $params[] = $image;
        }

        $query .= " WHERE id = ?";
        $params[] = $petId;

        $stmt = $conn->prepare($query);
        
        // Bind parameters
        for ($i = 0; $i < count($params); $i++) {
            $paramType = is_int($params[$i]) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($i + 1, $params[$i], $paramType);
        }

        // Execute the prepared statement
        if ($stmt->execute()) {
            header('Location: admin_dashboard_view_pets.php'); // Redirect to the dashboard or another page
            exit;
        } else {
            echo "Error: Unable to update pet.";
        }

        $stmt->close();
        $conn = null;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
