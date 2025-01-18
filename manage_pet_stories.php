<?php
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();
$errors = [];
$success = '';

// Handle deletion of a pet story
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM pet_stories WHERE id = ?");
    $stmt->execute([$delete_id]);
    $success = "Pet story deleted successfully.";
}

// Handle form submission for adding/editing pet stories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $pet_name = $_POST['pet_name'];
    $pet_age = $_POST['pet_age'];
    $story_id = isset($_POST['story_id']) ? $_POST['story_id'] : '';

    if (empty($title) || empty($content) || empty($pet_name) || empty($pet_age)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        if ($story_id) {
            // Update existing pet story
            $stmt = $pdo->prepare("UPDATE pet_stories SET title = ?, content = ?, pet_name = ?, pet_age = ? WHERE id = ?");
            $stmt->execute([$title, $content, $pet_name, $pet_age, $story_id]);

            // Handle file uploads
            if (!empty($_FILES['images']['name'][0])) {
                $stmt = $pdo->prepare("DELETE FROM pet_images WHERE story_id = ?");
                $stmt->execute([$story_id]);

                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    $fileName = $_FILES['images']['name'][$index];
                    $fileTmpPath = $tmpName;
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = './uploads/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $stmt = $pdo->prepare("INSERT INTO pet_images (story_id, image) VALUES (?, ?)");
                        $stmt->execute([$story_id, $newFileName]);
                    } else {
                        $errors[] = "There was an error moving the uploaded file.";
                    }
                }
            }

            $success = "Pet story updated successfully.";
        } else {
            // Insert new pet story
            $stmt = $pdo->prepare("INSERT INTO pet_stories (title, content, pet_name, pet_age) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $pet_name, $pet_age]);
            $story_id = $pdo->lastInsertId();

            // Handle file uploads
            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                    $fileName = $_FILES['images']['name'][$index];
                    $fileTmpPath = $tmpName;
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = './uploads/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $stmt = $pdo->prepare("INSERT INTO pet_images (story_id, image) VALUES (?, ?)");
                        $stmt->execute([$story_id, $newFileName]);
                    } else {
                        $errors[] = "There was an error moving the uploaded file.";
                    }
                }
            }

            $success = "Pet story added successfully.";
        }
    }
}

// Fetch all pet stories
$stmt = $pdo->query("SELECT * FROM pet_stories");
$pet_stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch images for each pet story
$pet_images = [];
foreach ($pet_stories as $story) {
    $stmt = $pdo->prepare("SELECT * FROM pet_images WHERE story_id = ?");
    $stmt->execute([$story['id']]);
    $pet_images[$story['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pet Stories</title>
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

        .modal .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>

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
            <h2 class="text-center">Manage Pet Stories</h2>

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

            <!-- Form to add pet story -->
            <form method="post" action="manage_pet_stories.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Story Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="pet_name">Pet Name</label>
                    <input type="text" class="form-control" id="pet_name" name="pet_name" required>
                </div>
                <div class="form-group">
                    <label for="pet_age">Pet Age</label>
                    <input type="text" class="form-control" id="pet_age" name="pet_age" required>
                </div>
                <div class="form-group">
                    <label for="content">Story Content</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="images">Upload Images (Max 3)</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary" id="saveStoryButton">Save Story</button>
            </form>

            <script>
                document.getElementById('saveStoryButton').addEventListener('click', function (event) {
                    if (!confirm('Are you sure you want to save this story?')) {
                        event.preventDefault();
                    }
                });
            </script>

        </div>

        <h2 class="text-center">All Pet Stories</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Pet Name</th>
                    <th>Pet Age</th>
                    <th>content</th>
                    <th>images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pet_stories as $story): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($story['id']); ?></td>
                        <td><?php echo htmlspecialchars($story['title']); ?></td>
                        <td><?php echo htmlspecialchars($story['pet_name']); ?></td>
                        <td><?php echo htmlspecialchars($story['pet_age']); ?></td>
                        <td><?php echo htmlspecialchars($story['content']); ?></td>
                        <td>
                            <?php if (!empty($pet_images[$story['id']])): ?>
                                <?php foreach ($pet_images[$story['id']] as $image): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($image['image']); ?>" alt="Pet Image" width="50">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal"
                                data-id="<?php echo $story['id']; ?>"
                                data-title="<?php echo htmlspecialchars($story['title']); ?>"
                                data-content="<?php echo htmlspecialchars($story['content']); ?>"
                                data-petname="<?php echo htmlspecialchars($story['pet_name']); ?>"
                                data-petage="<?php echo htmlspecialchars($story['pet_age']); ?>">Edit</button>
                            <a href="manage_pet_stories.php?delete_id=<?php echo $story['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this story?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pet Story</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="manage_pet_stories.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="story_id" name="story_id">
                        <div class="form-group">
                            <label for="edit_title">Story Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_content">Story Content</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="5"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_pet_name">Pet Name</label>
                            <input type="text" class="form-control" id="edit_pet_name" name="pet_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_pet_age">Pet Age</label>
                            <input type="text" class="form-control" id="edit_pet_age" name="pet_age" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_images">Upload Images (Max 3)</label>
                            <input type="file" class="form-control" id="edit_images" name="images[]" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveChangesButton">Save Changes</button>
                    </div>
                </form>
                <script>
                    document.getElementById('saveChangesButton').addEventListener('click', function (event) {
                        if (!confirm('Are you sure you want to update this story?')) {
                            event.preventDefault();
                        }
                    });
                </script>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var title = button.data('title');
            var content = button.data('content');
            var petName = button.data('petname');
            var petAge = button.data('petage');

            var modal = $(this);
            modal.find('#story_id').val(id);
            modal.find('#edit_title').val(title);
            modal.find('#edit_content').val(content);
            modal.find('#edit_pet_name').val(petName);
            modal.find('#edit_pet_age').val(petAge);
        });
    </script>
</body>

</html>