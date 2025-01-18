<?php
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();
$errors = [];
$success = '';

// Handle deletion of a vacancy
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM job_vacancies WHERE id = ?");
    $stmt->execute([$delete_id]);
    $success = "Job vacancy deleted successfully.";
}

// Handle form submission for adding/editing vacancies
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $vacancy_id = isset($_POST['vacancy_id']) ? $_POST['vacancy_id'] : '';

    // Handle file upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $image = $newFileName;
        } else {
            $errors[] = "There was an error moving the uploaded file.";
        }
    }

    if (empty($title) || empty($description) || empty($location) || empty($salary)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        if ($vacancy_id) {
            // Update existing vacancy
            if ($image) {
                $stmt = $pdo->prepare("UPDATE job_vacancies SET title = ?, description = ?, location = ?, salary = ?, image = ? WHERE id = ?");
                $stmt->execute([$title, $description, $location, $salary, $image, $vacancy_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE job_vacancies SET title = ?, description = ?, location = ?, salary = ? WHERE id = ?");
                $stmt->execute([$title, $description, $location, $salary, $vacancy_id]);
            }
            $success = "Job vacancy updated successfully.";
        } else {
            // Insert new vacancy
            $stmt = $pdo->prepare("INSERT INTO job_vacancies (title, description, location, salary, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $salary, $image]);
            $success = "Job vacancy added successfully.";
        }
    }
}

// Fetch all job vacancies
$stmt = $pdo->query("SELECT * FROM job_vacancies");
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Vacancies</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Include your stylesheet -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       


        body {
         font-family:'Comfortaa', cursive;
  
    overflow-x: hidden;
    height: 100vh;
    margin: 0;
    display: flex;
}

#wrapper {
    display: flex;
    width: 100%;
}

#sidebar-wrapper {
    height: 100vh;
    overflow-y: auto;
    width: 250px;
    flex-shrink: 0;
    background-color: #4C056C; /* Change this color to your desired background color */
    color: #ffffff; /* Ensures text is readable on the new background */
}

.profile-icon {
    width: 50px; /* Adjust the size as needed */
    height: 50px; /* Adjust the size as needed */
    border-radius: 50%; /* Makes the icon circular */
    margin-right: 10px; /* Space between the icon and text */
    vertical-align: middle; /* Aligns the icon with the text */
}

/* Optional: Add styles to remove underline from the link and ensure the icon is inline */
.sidebar-heading a {
    text-decoration: none;
    display: inline-block;
}

#page-content-wrapper {
    width: 100%;
    overflow-y: auto;
    padding: 20px;
}

.sidebar-heading {
    padding: 10px 15px;
    font-size: 1.25rem;
    color: #ffffff; /* Ensures text color is readable on the new background */
}

.list-group-item {
    border: none;
    padding: 15px 20px;
    background-color: #4C056C; /* Matches the sidebar background color */
    color: #ffffff; /* Ensures text color is readable */
}

.list-group-item:hover {
    color: white;
    background-color: #670D8F; /* Slightly lighter color for hover effect */
}

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
        }

        .form-container h2 {
            color: #4b0082;
        }

        .btn-primary {
            background-color: #9370db;
            border-color: #9370db;
        }

        .btn-primary:hover {
            background-color: #6a0dad;
            border-color: #6a0dad;
        }

        .form-control:focus {
            border-color: #9370db;
            box-shadow: 0 0 0 0.2rem rgba(147, 112, 219, 0.25);
        }

        .form-group label {
            color: #4b0082;
        }

        .alert-success {
            background-color: #88b04b;
            border-color: #88b04b;
            color: white;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            font-size: 2em;
            font-weight: bold;
            color: #4b0082;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table thead {
            background-color: #4b0082;
            color: white;
        }

        .table thead th {
            border: none;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f0e6ff;
        }

        .table tbody td {
            border: none;
        }

        .action-buttons {
            white-space: nowrap;
            width: 1%;
        }
    </style>
</head>

<body>


<body>
      <div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <a href="profile_page.html">
            <img style="margin-left: 60px; margin-bottom:20px; margin-top: 20px;" src="images/profile.jpg" alt="Profile Icon" class="profile-icon">
        </a><br>
        
    </div>
     <div class="list-group list-group-flush">
               <a style="font-size:20px;" href="admin_dashboard.php" class="list-group-item list-group-item-action">Admin Dashboard</a>
            <a href="admin_dashboard_view_users.php" class="list-group-item list-group-item-action">User Details</a>
             <a href="admin_dashboard_add_pets.php" class="list-group-item list-group-item-action">Add Pet Details</a>
              <a href="admin_dashboard_view_pets.php" class="list-group-item list-group-item-action">View Pet Details</a>
               <a href="pet_transaction.php" class="list-group-item list-group-item-action">Pet Transactions</a>
               <a href="fetch_pets.php" class="list-group-item list-group-item-action">Buy Pets</a>
               <a href="view_bookings.php" class="list-group-item list-group-item-action">Pet Meetings</a>
                  <a href="waiting_list_admin.php" class="list-group-item list-group-item-action">Waiting List</a>

                   <a href="admin_events.php" class="list-group-item list-group-item-action">Add Events</a>
                    <a href="admin_retrieve_events.php" class="list-group-item list-group-item-action">Seat booking</a>
                <a href="manage_pet_stories.php" class="list-group-item list-group-item-action">Pet of the Day</a>

            <a href="calender.php" class="list-group-item list-group-item-action">Calendar</a>
            
           
            <a href="notices.php" class="list-group-item list-group-item-action">Notices</a>


            <a href="admin_lostfound_pets.php" class="list-group-item list-group-item-action">Lost/Found Pets</a>
            
          
             <a href="manage_vacancies.php" class="list-group-item list-group-item-action">Manage Vacancies</a>
             <a href="admin_job_applications.php" class="list-group-item list-group-item-action">Job Applications</a>

            <a href="admin_view_posts.php" class="list-group-item list-group-item-action">Pet Experiences</a>
                
                   
               
        </div>
    </div>

        
    </div>
</div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
               
            </nav>



    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Manage Job Vacancies</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form to add job vacancy -->
            <form id="vacancyForm" method="post" action="manage_vacancies.php" enctype="multipart/form-data">
                <input type="hidden" name="vacancy_id" id="vacancy_id">
                <div class="form-group">
                    <label for="title">Job Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="text" class="form-control" id="salary" name="salary" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary" onclick="return confirmSubmission();">Submit</button>
            </form>
        </div>
        <!-- List of job vacancies -->
        <h2 class="text-center">Current Job Vacancies</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Image</th>
                    <th class="action-buttons">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vacancies as $vacancy): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vacancy['id']); ?></td>
                        <td><?php echo htmlspecialchars($vacancy['title']); ?></td>
                        <td><?php echo htmlspecialchars($vacancy['description']); ?></td>
                        <td><?php echo htmlspecialchars($vacancy['location']); ?></td>
                        <td><?php echo htmlspecialchars($vacancy['salary']); ?></td>
                        <td>
                            <?php if (!empty($vacancy['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($vacancy['image']); ?>" alt="Job Image" width="100">
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-warning" onclick="editVacancy(<?php echo htmlspecialchars(json_encode($vacancy)); ?>)">Edit</button>
                            <a href="manage_vacancies.php?delete_id=<?php echo $vacancy['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this vacancy?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="manage_vacancies.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Job Vacancy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="vacancy_id" id="modal_vacancy_id">
                        <div class="form-group">
                            <label for="modal_title">Job Title</label>
                            <input type="text" class="form-control" id="modal_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_description">Description</label>
                            <textarea class="form-control" id="modal_description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="modal_location">Location</label>
                            <input type="text" class="form-control" id="modal_location" name="location" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_salary">Salary</label>
                            <input type="text" class="form-control" id="modal_salary" name="salary" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_image">Image</label>
                            <input type="file" class="form-control-file" id="modal_image" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmSubmission() {
            return confirm('Are you sure you want to submit this form?');
        }

        function editVacancy(vacancy) {
            $('#modal_vacancy_id').val(vacancy.id);
            $('#modal_title').val(vacancy.title);
            $('#modal_description').val(vacancy.description);
            $('#modal_location').val(vacancy.location);
            $('#modal_salary').val(vacancy.salary);
            $('#editModal').modal('show');
        }

        // Attach confirmSubmission function to form submission
        document.getElementById('vacancyForm').onsubmit = confirmSubmission;
    </script>
</body>

</html>
