<?php
require 'dbConnector.php';

// Fetch all bookings and their corresponding meetings if scheduled
$db = new dbConnector();
$pdo = $db->getConnection();

$sql = "SELECT bookings.*, users.first_name, users.last_name, users.email, meetings.id AS meeting_id 
        FROM bookings 
        JOIN users ON bookings.user_id = users.id 
        LEFT JOIN meetings ON bookings.id = meetings.booking_id 
        ORDER BY bookings.booked_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Redirect flag
$redirect = false;

// Handle meeting scheduling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $zoom_link = $_POST['zoom_link'];
    $meeting_id = $_POST['meeting_id'];
    $passcode = $_POST['passcode'];
    $booking_id = $_POST['booking_id'];

    $sql = "INSERT INTO meetings (user_id, user_name, user_email, zoom_link, meeting_id, passcode, booking_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $user_name, $user_email, $zoom_link, $meeting_id, $passcode, $booking_id]);

    $redirect = true;
}

// Handle meeting editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $meeting_id = $_POST['meeting_id'];
    $zoom_link = $_POST['zoom_link'];
    $meeting_id_input = $_POST['meeting_id_input'];
    $passcode = $_POST['passcode'];

    $sql = "UPDATE meetings SET zoom_link = ?, meeting_id = ?, passcode = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$zoom_link, $meeting_id_input, $passcode, $meeting_id]);

    $redirect = true;
}

// Redirect if needed
if ($redirect) {
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Fetch all meetings
$sql = "SELECT meetings.*, bookings.date, bookings.time_slot 
        FROM meetings 
        JOIN bookings ON meetings.booking_id = bookings.id 
        ORDER BY meetings.scheduled_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            font-family:'Comfortaa', cursive;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            border: none;
        }

        body {
            background-color: #f3f3f3;
            
        }

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

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
        }

        .btn-primary {
            background-color: #4b0082;
            border-color: #4b0082;
        }

        .btn-primary:hover {
            background-color: #371061;
            border-color: #371061;
        }

        .btn-secondary {
            background-color: #c494e3;
            border-color: #c494e3;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #a872d1;
            border-color: #a872d1;
        }

        .btn-warning {
            background-color: #4b0082;
            border-color: #4b0082;
            color: white;
        }

        .btn-warning:hover {
            background-color: #371061;
            border-color: #371061;
        }

        .btn-danger:hover {
            background-color: #bd2130;
            border-color: #bd2130;
        }
    </style>

    <script>
        function scheduleMeeting(userId, userName, userEmail, bookingId, date, timeSlot) {
    document.getElementById('user_id').value = userId;
    document.getElementById('user_name').value = userName;
    document.getElementById('user_email').value = userEmail;
    document.getElementById('booking_id').value = bookingId;
    document.getElementById('meeting_date').value = date;
    document.getElementById('meeting_time_slot').value = timeSlot;
    $('#scheduleModal').modal('show');
}


        function editMeeting(meetingId, zoomLink, meetingIdInput, passcode) {
            document.getElementById('edit_meeting_id').value = meetingId;
            document.getElementById('edit_zoom_link').value = zoomLink;
            document.getElementById('edit_meeting_id_input').value = meetingIdInput;
            document.getElementById('edit_passcode').value = passcode;
            $('#editModal').modal('show');
        }

        // Function to change the button to 'Meeting Scheduled' and disable it
        function changeButtonToScheduled(bookingId) {
            const button = document.querySelector(`button[data-booking-id="${bookingId}"]`);
            button.classList.remove('btn-primary');
            button.classList.add('btn-secondary');
            button.textContent = 'Meeting Scheduled';
            button.disabled = true;
        }
    </script>
</head>
<body style=" font-family:'Comfortaa', cursive;">

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
            <div class="container-fluid">
               
                <div class="row">


    <div class="container">
        <h2 class="text-center">All Bookings</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $booking): ?>
    <tr>
        <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
        <td><?php echo htmlspecialchars($booking['email']); ?></td>
        <td><?php echo htmlspecialchars($booking['date']); ?></td>
        <td><?php echo htmlspecialchars($booking['time_slot']); ?></td>
        <td>
            <?php if ($booking['meeting_id']): ?>
                <button class="btn btn-secondary" disabled>Meeting Scheduled</button>
            <?php else: ?>
                <button class="btn btn-primary" data-booking-id="<?php echo $booking['id']; ?>" 
                        onclick="scheduleMeeting('<?php echo $booking['user_id']; ?>', '<?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?>', '<?php echo htmlspecialchars($booking['email']); ?>', '<?php echo $booking['id']; ?>', '<?php echo $booking['date']; ?>', '<?php echo $booking['time_slot']; ?>')">Schedule Meeting</button>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

            </tbody>
        </table>

       <!-- Modal for Scheduling Meeting -->
<!-- Modal for Scheduling Meeting -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="" onsubmit="return confirmAndSubmit(this);">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id">
                    <input type="hidden" id="booking_id" name="booking_id">
                    <input type="hidden" id="date" name="date">
                    <input type="hidden" id="time_slot" name="time_slot">
                    <input type="hidden" name="schedule" value="1">
                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_email">User Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" readonly>
                    </div>
                    <div class="form-group">
                        <label for="meeting_date">Date</label>
                        <input type="text" class="form-control" id="meeting_date" name="meeting_date" readonly>
                    </div>
                    <div class="form-group">
                        <label for="meeting_time_slot">Time Slot</label>
                        <input type="text" class="form-control" id="meeting_time_slot" name="meeting_time_slot" readonly>
                    </div>
                    <div class="form-group">
                        <label for="zoom_link">Zoom Link</label>
                        <input type="text" class="form-control" id="zoom_link" name="zoom_link" required>
                    </div>
                    <div class="form-group">
                        <label for="meeting_id">Meeting ID</label>
                        <input type="text" class="form-control" id="meeting_id" name="meeting_id" required>
                    </div>
                    <div class="form-group">
                        <label for="passcode">Passcode</label>
                        <input type="text" class="form-control" id="passcode" name="passcode" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function confirmAndSubmit(form) {
        if (confirm('Are you sure you want to schedule this meeting?')) {
            // Show success message after form submission
            setTimeout(function() {
                alert('Meeting successfully scheduled!');
            }, 500);
            return true;
        } else {
            return false;
        }
    }
</script>

<h2 class="text-center mt-5">Scheduled Meetings</h2>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Zoom Link</th>
            <th>Meeting ID</th>
            <th>Passcode</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($meetings as $meeting): ?>
            <tr>
                <td><?php echo htmlspecialchars($meeting['user_name']); ?></td>
                <td><?php echo htmlspecialchars($meeting['user_email']); ?></td>
                <td><?php echo htmlspecialchars($meeting['date']); ?></td>
                <td><?php echo htmlspecialchars($meeting['time_slot']); ?></td>
                <td><a href="<?php echo htmlspecialchars($meeting['zoom_link']); ?>" target="_blank">Zoom Link</a></td>
                <td><?php echo htmlspecialchars($meeting['meeting_id']); ?></td>
                <td><?php echo htmlspecialchars($meeting['passcode']); ?></td>
                <td>
                    <button class="btn btn-warning" onclick="editMeeting('<?php echo $meeting['id']; ?>', '<?php echo htmlspecialchars($meeting['zoom_link']); ?>', '<?php echo htmlspecialchars($meeting['meeting_id']); ?>', '<?php echo htmlspecialchars($meeting['passcode']); ?>')">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



        <!-- Modal for Editing Meeting -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Meeting</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_meeting_id" name="meeting_id">
                            <input type="hidden" name="edit" value="1">
                            <div class="form-group">
                                <label for="edit_zoom_link">Zoom Link</label>
                                <input type="text" class="form-control" id="edit_zoom_link" name="zoom_link" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_meeting_id_input">Meeting ID</label>
                                <input type="text" class="form-control" id="edit_meeting_id_input" name="meeting_id_input" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_passcode">Passcode</label>
                                <input type="text" class="form-control" id="edit_passcode" name="passcode" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Meeting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Best Pet Sellers
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

