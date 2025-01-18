<?php
session_start(); // Ensure the session is started
require 'dbConnector.php';

$db = new dbConnector();
$con = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEvent'])) {
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $totalSeats = $_POST['totalSeats'];

    $query = "INSERT INTO limited_seat_events (event_name, event_description, total_seats, available_seats) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    
    // Bind parameters
    $stmt->bindValue(1, $eventName);
    $stmt->bindValue(2, $eventDescription);
    $stmt->bindValue(3, $totalSeats);
    $stmt->bindValue(4, $totalSeats); // Initially, available seats equals total seats

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Event added successfully!";
    } else {
        $_SESSION['message'] = "Failed to add event.";
    }

    // Redirect to the same page after form submission
    header('Location: admin_events.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Comfortaa', cursive;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            border: none;
        }
        body {
            background-color: #f8f9fa;
        }

        .btn-purple {
            background-color: #4b0082;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a1da8;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #4b0082;
        }

        .form-container {
            background-color: #ffffff; /* White background for the form */
            padding: 20px; /* Add padding */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

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

    </style>
</head>

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

    <div class="container">
        <h2 class="text-center">Add a New Event with Limited Seats</h2>
        <div class="form-container">
            <form id="eventForm" method="post" action="">
                <div class="form-group">
                    <label for="eventName">Event Name:</label>
                    <input type="text" class="form-control" name="eventName" id="eventName" required>
                </div>
                <div class="form-group">
                    <label for="eventDescription">Event Description:</label>
                    <textarea class="form-control" name="eventDescription" id="eventDescription" required></textarea>
                </div>
                <div class="form-group">
                    <label for="totalSeats">Total Seats:</label>
                    <input type="number" class="form-control" name="totalSeats" id="totalSeats" required>
                </div>
                <button type="submit" name="addEvent" class="btn btn-purple">Add Event</button>
            </form>
        </div>
    </div>

    <script>
        // Show SweetAlert success/error message after redirection
        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                title: 'Message',
                text: '<?php echo $_SESSION['message']; ?>',
                icon: '<?php echo $_SESSION['message'] == "Event added successfully!" ? "success" : "error"; ?>',
                confirmButtonColor: '#4b0082'
            });
            <?php unset($_SESSION['message']); // Unset session message after displaying ?>
        <?php endif; ?>
    </script>
</body>
</html>

