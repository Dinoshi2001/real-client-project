<?php
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

// Fetch all job vacancies
$stmt = $pdo->query("SELECT * FROM job_vacancies");
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);


session_start(); // Start the session


// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_form.php");
    exit;
}

$user = $_SESSION['user']; // User details from session

if (!isset($user['id'])) {
    die("User ID is not set in session. Please log in again.");
}


// Fetch available time slots and bookings
$db = new dbConnector();
$pdo = $db->getConnection();

// Fetch the first available meetup ID for simplicity
// Fetch the first available meetup ID for simplicity
$sql = "SELECT id FROM meetups ORDER BY created_at LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$meetup = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$meetup) {
    die("No meetups available. Please ensure the meetups table has entries.");
}

$meetupId = $meetup['id'];


$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$sql = "SELECT * FROM bookings WHERE meetup_id = ? AND date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$meetupId, $date]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bookedSlots = [];
foreach ($bookings as $booking) {
    $bookedSlots[] = $booking['time_slot'];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $timeSlot = $_POST['time_slot'];

    // Check if the time slot is booked by another user
    $sql = "SELECT COUNT(*) FROM bookings WHERE meetup_id = ? AND time_slot = ? AND date = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$meetupId, $timeSlot, $date]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $message = "Sorry, the selected time slot ($timeSlot) has just been booked by another user. Please choose another time slot.";
    } else {
        // Insert the booking
        $sql = "INSERT INTO bookings (user_id, meetup_id, time_slot, date) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user['id'], $meetupId, $timeSlot, $date])) {
            // Set success message in session
            $_SESSION['success_message'] = "Thank you for booking a virtual pet meetup! We will send a Zoom link via email. Let's meet on $date at $timeSlot.";
            header("Location: book_meetup.php?date=$date");
            exit();
        } else {
            $message = "An error occurred while booking the time slot.";
        }
    }
}

// Define available time slots
$start_time = new DateTime('09:00');
$end_time = new DateTime('17:00');
$interval = new DateInterval('PT30M'); // 30 minutes interval
$time_slots = [];

for ($time = clone $start_time; $time <= $end_time; $time->add($interval)) {
    $time_slots[] = $time->format('H:i:s');
}

// Display the success message if set
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}

// Fetch scheduled meetings for the user from the bookings and meetings table
$sql = "SELECT bookings.date, bookings.time_slot, meetings.zoom_link, meetings.meeting_id, meetings.passcode 
        FROM bookings 
        JOIN meetings ON bookings.id = meetings.booking_id 
        WHERE bookings.user_id = ? 
        ORDER BY bookings.date DESC, bookings.time_slot DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user['id']]);
$meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    /* Custom styles */
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: grey;
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #5B1D76;
    }

    .navbar {
      background-color: rgba(255, 255, 255, 0.2);
      position: fixed;
      display: flex;
      width: 100vw;
      justify-content: space-between;
      z-index: 100;
      backdrop-filter: blur(5px);
    }

    .navbar-nav {
      margin: 0 auto;
      display: flex;
      align-items: center;
      /* background-color: white; */
    }

    .navbar-center {
      background-color: rgba(255, 255, 255, 0.7);
      border-radius: 40px;
      display: flex;
      align-items: center;
      justify-content: space-evenly;
      padding: 5px 30px;
      gap: 5px;
      box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.2);
    }

    .navbar-right {
      display: flex;
      gap: 5px;
    }

    .nav-link {
      /* margin-right: 20px; */
      font-size: 16px;
      color: #5F0E85;
      /* border-bottom: solid 2px rgba(0, 0, 0, 0); */
      transition: 250ms;
    }

    .nav-link:hover {
      /* background-color: #5F0E85; */
      /* color: white; */
      /* border-bottom: solid 2px #5B1D76; */
      text-shadow: 0px 0px 25px white;
    }

    .navbar-brand img {
      margin-right: 20px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }

        .header-image {
            position: relative;
            width: 100%;
            height: 300px;
            background-image: url('images/puppy.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .header-image h1 {
            font-size: 3em;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 10px;
        }

       

        body {
            background-color: #f3f3f3;
        }

        .container {
            margin-top: 50px;
        }

        .header-image {
            width: 100%;
            height: 300px;
            background-image: url('images/dog.webp'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: -150px;
            padding: 20px;
        }

        .card-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #4b0082;
            margin-bottom: 10px;
        }

        .card-text {
            color: #333;
            margin-bottom: 10px;
        }

        .btn {
            border-radius: 5px;
        }

        .time-slot {
            margin: 5px;
            background-color: #4b0082;
            color: white;
        }

        .time-slot:hover {
            background-color: #9370DB; /* Light purple color */
        }

        .booked {
            background-color: #d9534f;
            color: white;
        }


        .meeting-card {
            margin-top: 20px;
        }

        .meeting-card .card-body {
            background-color: #f9f9f9;
        }

        .meeting-card .card-title {
            font-size: 1.2em;
            font-weight: bold;
        }

        .meeting-card .card-text {
            margin-bottom: 5px;
        }


        /* Footer */

    .footer {
      background-color: #5B1D76;
      color: white;
      padding: 20px 0;
      text-align: center;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .footer p {
      margin: 0;
      font-size: 1.1em;
    }

    .footer-socials {
      list-style-type: none;
      padding: 0;
      display: flex;
      gap: 15px;
    }

    .footer-socials li {
      display: inline;
    }

    .footer-socials a {
      color: white;
      font-size: 1.5em;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-socials a:hover {
      color: #1c83ff;
    }

    </style>
    <script>
        function confirmBooking(timeSlot) {
            if (confirm('Do you want to book this time slot?')) {
                document.getElementById('time_slot').value = timeSlot;
                document.getElementById('bookingForm').submit();
            }
        }
    </script>
</head>

<body style=" font-family:'Comfortaa', cursive;">


 



  <div class="project">

    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Your Shop" height="40" width="100">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav navbar-center">
          <li class="nav-item active">
            <a class="nav-link" href="homepage.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pet_of_the_day.php">special pets</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              pets
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="userside_pets.php">Dogs</a>
              <a class="dropdown-item" href="userside_pets_cats.php">Cats</a>
              <a class="dropdown-item" href="userside_pets_birds.php">Birds</a>
              <a class="dropdown-item" href="userside_pets_rabbits.php">Rabbits</a>

            </div>
          </li>
          

          <li class="nav-item">
            <a class="nav-link" href="index.php">Pet Quiz</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="book_meetup.php">Pet Meetups</a>
          </li>
         

          


            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Waiting List
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="waiting_list.php">Add Waiting List</a>
              <a class="dropdown-item" href="waiting_list_users.php">View Waiting List</a>
         
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="view_notices.php">Notices</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calender_userside.php">calendar</a>
          </li>

          

           <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Lost/Found pets
            </a>

         <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="manage_lostfound_pets.php">Report Lost/Found Pets</a>
              <a class="dropdown-item" href="lost_pets.php">Lost Pets</a>
              <a class="dropdown-item" href="found_pets.php">Found Pets</a>
              

            </div>
          </li>

           <li class="nav-item">
            <a class="nav-link" href="add_pet.php">Sell your pets</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="job_vacancies.php">Careers</a>
          </li>

         


          

          

        </ul>
        <ul class="navbar-nav navbar-right ml-auto">
          



          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal"><i class="fas fa-user fa-lg"></i></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>


    <?php
    // Check if user is logged in
    if (isset($_SESSION['user'])) {
      // Get user data from session
      $user = $_SESSION['user'];
    ?>




    <?php } else {
      // If user is not logged in, display a message or redirect to login page

    }
    ?>

    <input type="hidden" id="is-logged-in" value="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">

    <!-- Modal Structure -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profileModalLabel">Update Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="updateProfileForm" action="update_user.php" method="POST">
            <div class="modal-body">
              <!-- Success message placeholder -->
              <div id="successMessage" class="alert alert-success" style="display:none;">
                Profile updated successfully!
              </div>
              <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" class="form-control" value="<?php echo $user['id']; ?>" id="userid" name="userid" readonly>
              </div>
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" value="<?php echo $user['first_name']; ?>" name="first_name" required>
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" value="<?php echo $user['last_name']; ?>" name="last_name" required>
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>" name="email" required>
              </div>
              <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" value="<?php echo $user['contact_number']; ?>" name="contact_number" required>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" value="<?php echo $user['address']; ?>" name="address" required>
              </div>
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" value="<?php echo $user['dob']; ?>" name="dob" required>
              </div>
              <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" value="<?php echo $user['age']; ?>" name="age" required>
              </div>
              <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" class="form-control" id="nic" value="<?php echo $user['nic']; ?>" name="nic" required>
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" class="form-control" id="gender" value="<?php echo $user['gender']; ?>" name="gender" required>
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


    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage && successMessage.style.display === 'block') {
          setTimeout(function() {
            window.location.href = 'your_profile_page.php'; // Adjust the URL as needed
          }, 3000);
        }
      });
    </script>


    <div class="header-image"></div>
    <div class="container">
        <div class="card">
            <h2 class="card-title text-center">Book a Virtual Pet Meetup</h2>
            <p class="card-text text-center">Interested in meeting our adorable pets before making a decision? Book a virtual meetup to see their behavior and interact with them online. Choose a suitable time slot from the available options below and enjoy a fun and informative session with your potential new pet!</p>
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form id="dateForm" method="GET" action="">
                <div class="form-group">
                    <label for="date" class="card-text">Select Date:</label>
                    <input type="date" id="date" name="date" class="form-control"
                           value="<?php echo htmlspecialchars($date); ?>"
                           min="<?php echo date('Y-m-d'); ?>"
                           max="<?php echo date('Y-m-d', strtotime('+1 month')); ?>"
                           onchange="document.getElementById('dateForm').submit();">
                </div>
            </form>
            <form id="bookingForm" method="POST" action="">
                <input type="hidden" id="time_slot" name="time_slot">
            </form>
            <div class="text-center">
                <?php foreach ($time_slots as $time_slot): ?>
                    <button class="btn time-slot <?php echo in_array($time_slot, $bookedSlots) ? 'booked' : ''; ?>"
                            <?php echo in_array($time_slot, $bookedSlots) ? 'disabled' : ''; ?>
                            onclick="confirmBooking('<?php echo $time_slot; ?>')">
                        <?php echo $time_slot; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Scheduled Meetings Section -->
        <h2 class="card-title text-center mt-5">My Scheduled Meetings</h2>
        <?php if (!empty($meetings)): ?>
            <?php foreach ($meetings as $meeting): ?>
                <div class="card meeting-card">
                    <div class="card-body">
                        <h5 class="card-title">Scheduled Meeting</h5>
                        <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($meeting['date']); ?></p>
                        <p class="card-text"><strong>Time Slot:</strong> <?php echo htmlspecialchars($meeting['time_slot']); ?></p>
                        <p class="card-text"><strong>Zoom Link:</strong> <a href="<?php echo htmlspecialchars($meeting['zoom_link']); ?>" target="_blank"><?php echo htmlspecialchars($meeting['zoom_link']); ?></a></p>
                        <p class="card-text"><strong>Meeting ID:</strong> <?php echo htmlspecialchars($meeting['meeting_id']); ?></p>
                        <p class="card-text"><strong>Passcode:</strong> <?php echo htmlspecialchars($meeting['passcode']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center mt-4">No meetings scheduled</div>
        <?php endif; ?>
</div>

<br><br>
  <!--footer start-->
    <footer class="footer">
      <div class="footer-content">
        <p>© 2024 Best Pet Sellers</p>
        <ul class="footer-socials">
          <li>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-twitter"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
          </li>
          <li>
            <a href="#"><i class="fab fa-linkedin"></i></a>
          </li>
        </ul>
      </div>
    </footer>

    <!--footer end-->


<!--footer end-->

    </div>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
