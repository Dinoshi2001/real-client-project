<?php

require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

// Fetch all job applications with user details
$stmt = $pdo->query("
    SELECT 
        u.first_name, u.last_name, u.age, u.gender, u.address, u.nic, u.email, u.contact_number, 
        ja.resume_path, jv.title AS job_title 
    FROM 
        job_applications ja 
    JOIN 
        users u ON ja.user_id = u.id 
    JOIN 
        job_vacancies jv ON ja.vacancy_id = jv.id
");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
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
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        /* Adjust column widths */
        .table th,
        .table td {
            white-space: nowrap;
            padding: 10px;
        }

        .table th:first-child,
        .table td:first-child {
            width: 40px; /* First Name */
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 100px; /* Last Name */
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 50px; /* Age */
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 70px; /* Gender */
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 70px; /* Address */
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 100px; /* NIC */
        }

        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 150px; /* Email */
        }

        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 100px; /* Contact Number */
        }

        .table th:nth-child(9),
        .table td:nth-child(9) {
            width: 100px; /* Job Title */
        }

        .table th:nth-child(10),
        .table td:nth-child(10) {
            width: 100px; /* Resume */
        }

        .table-responsive {
            overflow-x: auto;
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




    <div class="container">
        <h2 class="text-center">Job Applications</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>NIC</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Job Title</th>
                        <th>Resume</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($application['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($application['age']); ?></td>
                            <td><?php echo htmlspecialchars($application['gender']); ?></td>
                            <td><?php echo htmlspecialchars($application['address']); ?></td>
                            <td><?php echo htmlspecialchars($application['nic']); ?></td>
                            <td><?php echo htmlspecialchars($application['email']); ?></td>
                            <td><?php echo htmlspecialchars($application['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($application['resume_path']); ?>" target="_blank">View Resume</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
