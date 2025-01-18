<?php
session_start();
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

// Fetch all lost pets
$stmt = $pdo->prepare("SELECT * FROM lostfind_pets WHERE status = 'lost'");
$stmt->execute();
$lost_pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all found pets
$stmt = $pdo->prepare("SELECT * FROM lostfind_pets WHERE status = 'found'");
$stmt->execute();
$found_pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Lost and Found Pets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            height: 100vh;
            margin: 0; /* Remove the top margin */
            display: flex;
            overflow-x: hidden;
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

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh; /* Ensure the wrapper takes full height */
        }

        #sidebar-wrapper {
            height: 100vh;
            overflow-y: auto;
            width: 250px;
            flex-shrink: 0;
            background-color: #4C056C; /* Sidebar background color */
            color: #ffffff;
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            vertical-align: middle;
        }

        .sidebar-heading a {
            text-decoration: none;
            display: inline-block;
        }

        .sidebar-heading {
            padding: 10px 15px;
            font-size: 1.25rem;
            color: #ffffff;
        }

        .list-group-item {
            border: none;
            padding: 15px 20px;
            background-color: #4C056C;
            color: #ffffff;
        }

        .list-group-item:hover {
            color: white;
            background-color: #670D8F;
        }

        #page-content-wrapper {
            width: 100%;
            overflow-y: auto; /* Enable scrolling in the content area */
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 15px;
        }

        h2 {
            font-size: 2em;
            font-weight: bold;
            color: #4b0082;
            margin-bottom: 20px;
            text-align: center;
        }

        .table {
            width: 100%;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 50px;
        }

        .table thead {
            background-color: #4b0082;
            color: white;
        }

        .table thead th {
            border: none;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background-color: #f0e6ff;
        }

        .table tbody td {
            border: none;
            padding: 15px;
            text-align: left;
        }

        img {
            max-width: 100px;
            border-radius: 10px;
        }

    </style>
</head>

<body>

<div id="wrapper">
    <!-- Sidebar -->
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

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container">
            <h2>Lost Pets</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pet Type</th>
                        <th>Breed</th>
                        <th>Description</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lost_pets as $pet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pet['pet_id']); ?></td>
                            <td><?php echo htmlspecialchars($pet['pet_type']); ?></td>
                            <td><?php echo htmlspecialchars($pet['pet_brand']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($pet['description'])); ?></td>
                            <td><img src="<?php echo htmlspecialchars($pet['images']); ?>" alt="Pet Image"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Found Pets</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pet Type</th>
                        <th>Breed</th>
                        <th>Description</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($found_pets as $pet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pet['pet_id']); ?></td>
                            <td><?php echo htmlspecialchars($pet['pet_type']); ?></td>
                            <td><?php echo htmlspecialchars($pet['pet_brand']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($pet['description'])); ?></td>
                            <td><img src="<?php echo htmlspecialchars($pet['images']); ?>" alt="Pet Image"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>
 