<?php
require 'dbConnector.php';

$db = new dbConnector();
$con = $db->getConnection();

function fetchPetPosts($con)
{
    try {
        $query = "SELECT p.*, u.first_name, u.last_name 
                  FROM pet_posts p 
                  JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exc) {
        die("Error: " . $exc->getMessage());
    }
}

function deletePetPost($con, $postId)
{
    try {
        $query = "DELETE FROM pet_posts WHERE post_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    } catch (PDOException $exc) {
        die("Error: " . $exc->getMessage());
    }
}

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $postId = intval($_GET['delete']);
    $deleted = deletePetPost($con, $postId);

    if ($deleted) {
        header("Location: ?deleted=true");
    } else {
        header("Location: ?error=true");
    }
    exit;
}

$petPosts = fetchPetPosts($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Pet Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <style>


            /* styles.css */

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

.bg-primary, .bg-success, .bg-warning, .bg-danger {
    padding: 20px;
    border-radius: 5px;
}

      body {
           
            background-color: #f9f9f9;
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

        .btn-purple {
            background-color: #4b0082;
            color: #fff;
        }

        .btn-purple:hover {
            background-color: #5c109a;
            color: #fff;
        }

        .btn {
            cursor: pointer;
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


    <div class="container">
        <h2 class="text-center" style="color: #4b0082;">Manage Pet Posts</h2>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Posted By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($petPosts)): ?>
                    <?php foreach ($petPosts as $index => $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($index + 1); ?></td>
                            <td><?php echo htmlspecialchars($post['post_title']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></td>
                            <td>
                                <?php if (!empty($post['post_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($post['post_image']); ?>" style="max-width: 150px; height: auto;" alt="Post Image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                            <td>
                                <button type="button" class="btn btn-purple btn-sm" onclick="confirmDeletion(<?php echo htmlspecialchars($post['post_id']); ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No posts available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmDeletion(postId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4b0082',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete the post
                    window.location.href = `?delete=${postId}`;
                }
            });
        }

        // Display success or error message if any
        <?php if (isset($_GET['deleted'])): ?>
            Swal.fire({
                title: 'Notification',
                text: "Post deleted successfully.",
                icon: 'success',
                confirmButtonColor: '#4b0082'
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });
        <?php elseif (isset($_GET['error'])): ?>
            Swal.fire({
                title: 'Notification',
                text: "Failed to delete post.",
                icon: 'error',
                confirmButtonColor: '#4b0082'
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });
        <?php endif; ?>
    </script>
</body>

</html>


