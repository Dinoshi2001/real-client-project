<?php
session_start();

require 'dbConnector.php';
$message = '';

// Check if the user is an admin

// Handle form submission for adding a notice
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_notice'])) {
    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Collect form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $target_dir = "uploads/";
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $target_file = $target_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }

    // Insert data into database using prepared statements
    $sql = "INSERT INTO notices (title, description, image) VALUES (:title, :description, :image)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':image' => $image_path,
        ]);
        $message = "Notice added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch notices from the database
$db = new dbConnector();
$pdo = $db->getConnection();
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$notices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       

        body {
    overflow-x: hidden;
    height: 100vh;
    margin: 0;
    display: flex;
     font-family:'Comfortaa', cursive;
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

        * {
            font-family: 'Comfortaa', cursive;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            border: none;
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

        .card-container {
            margin-top: 20px;
            color: #4b0082;
        }

        .card {
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card img {
            max-height: 200px;
            object-fit: cover;
        }

        .modal-content {
            font-family: 'Comfortaa', cursive;
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
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Add Notice</h2>
            <?php if ($message): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            <form id="noticeForm" method="post" action="notices.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="add_notice">Submit</button>
            </form>
        </div>

        <div class="card-container">
            <h2 class="text-center">Notices</h2>
            <div class="row">
                <?php foreach ($notices as $notice): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if ($notice['image']): ?>
                        <img src="<?php echo htmlspecialchars($notice['image']); ?>" class="card-img-top" alt="Notice Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($notice['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($notice['description']); ?></p>
                            <button class="btn btn-primary edit-btn" data-id="<?php echo $notice['id']; ?>" data-toggle="modal" data-target="#editModal">Edit</button>
                            <a href="delete_notice.php?id=<?php echo $notice['id']; ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Notice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editNoticeForm" method="post" action="edit_notice.php" enctype="multipart/form-data">
                        <input type="hidden" id="editNoticeId" name="noticeId">
                        <div class="form-group">
                            <label for="editTitle">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editImage">Upload Image</label>
                            <input type="file" class="form-control-file" id="editImage" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.edit-btn').on('click', function () {
                var noticeId = $(this).data('id');
                $.ajax({
                    url: 'get_notice_details.php',
                    method: 'GET',
                    data: { id: noticeId },
                    success: function (data) {
                        var notice = JSON.parse(data);
                        $('#editNoticeId').val(notice.id);
                        $('#editTitle').val(notice.title);
                        $('#editDescription').val(notice.description);
                    }
                });
            });
        });
    </script>
</body>

</html>

