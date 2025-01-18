<?php
session_start();
require 'dbConnector.php';

$db = new dbConnector();
$con = $db->getConnection();

// Fetch unique events
$queryEvents = "SELECT id, event_name FROM limited_seat_events";
$stmtEvents = $con->prepare($queryEvents);
$stmtEvents->execute();
$events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);

// Prepare to display registrations for each event
$registrationsByEvent = [];

foreach ($events as $event) {
    $eventId = $event['id'];

    // Fetch registrations for each event
    $queryRegistrations = "SELECT er.*, u.first_name, u.last_name, u.address, u.contact_number
                           FROM event_registrations er
                           JOIN users u ON er.user_id = u.id
                           WHERE er.event_id = ?
                           ORDER BY er.seat_number";
    $stmtRegistrations = $con->prepare($queryRegistrations);
    $stmtRegistrations->bindValue(1, $eventId);
    $stmtRegistrations->execute();
    $registrationsByEvent[$event['event_name']] = $stmtRegistrations->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registrations</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Comfortaa', cursive;
            overflow-x: hidden;
            height: 100vh;
            margin: 0;
            display: flex;
        }

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh; /* Ensure full height for the wrapper */
        }

        #sidebar-wrapper {
            height: 100vh; /* Sidebar takes full height */
            overflow-y: auto; /* Enable scrolling in the sidebar if needed */
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

        .sidebar-heading a {
            text-decoration: none;
            display: inline-block;
        }

        #page-content-wrapper {
            width: 100%;
            height: 100vh; /* Take full height */
            overflow-y: auto; /* Enable vertical scrolling for the content area */
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

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #4b0082;
            margin-bottom: 30px;
        }

        h4 {
            color: #4b0082;
            margin: 20px 0;
            border-bottom: 2px solid #4b0082;
            padding-bottom: 10px;
        }

        table {
            background-color: white;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #4b0082;
            color: white;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .text-center {
            margin: 10px 0;
        }

        .no-registrations {
            text-align: center;
            font-style: italic;
            color: #6c757d;
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
            <h2 class="text-center">User Registrations for Limited Seat Events</h2>

            <?php foreach ($registrationsByEvent as $eventName => $registrations): ?>
                <h4><?php echo htmlspecialchars($eventName); ?></h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seat Number</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($registrations)): ?>
                            <tr>
                                <td colspan="5" class="no-registrations">No registrations found for this event.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($registrations as $index => $registration): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($registration['seat_number']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['address']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['contact_number']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>
