<?php
session_start(); // Start session

// Check if the session contains user information and set user_id
if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
    $_SESSION['user_id'] = $_SESSION['user']['id'];
}

$message = "";
$messageType = "";

require 'dbConnector.php'; // Adjust path as per your file structure
$db = new dbConnector();
$pdo = $db->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $message = "User not logged in. Please log in to apply for the job.";
        $messageType = "danger";
    } else {
        $vacancy_id = $_POST['vacancy_id'];
        $user_id = $_SESSION['user_id']; // Get user ID from session
        $resume_path = ''; // Initialize variable to store file path

        // Handle file upload (resume)
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['resume']['tmp_name'];
            $fileName = $_FILES['resume']['name'];
            $fileSize = $_FILES['resume']['size'];
            $fileType = $_FILES['resume']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/resumes/'; // Directory to store resumes
            $resume_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $resume_path)) {
                // File uploaded successfully, proceed with database insertion
                $stmt = $pdo->prepare("INSERT INTO job_applications (vacancy_id, user_id, resume_path) VALUES (?, ?, ?)");
                $stmt->execute([$vacancy_id, $user_id, $resume_path]);

                if ($stmt->rowCount() > 0) {
                    // Application successfully submitted
                    $message = "Application submitted successfully.";
                    $messageType = "success";
                } else {
                    // Error in inserting data
                    $message = "Failed to submit application. Please try again.";
                    $messageType = "danger";
                }
            } else {
                // Error moving uploaded file
                $message = "Error uploading resume. Please try again.";
                $messageType = "danger";
            }
        } else {
            // No file uploaded or other file upload error
            $message = "Please upload your resume.";
            $messageType = "danger";
        }
    }
}

// Include this script in job_vacancies.php to handle form submission
echo json_encode(['message' => $message, 'messageType' => $messageType]);
?>
