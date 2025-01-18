<?php
session_start();
require 'dbConnector.php';

if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}

$db = new dbConnector();
$con = $db->getConnection();
$loggedInUserId = $_SESSION['user']['id'];

// Fetch all events
$query = "SELECT * FROM limited_seat_events WHERE available_seats > 0";
$stmt = $con->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's registered seats for events
$registrationQuery = "SELECT event_id, seat_number FROM event_registrations WHERE user_id = :userId";
$registrationStmt = $con->prepare($registrationQuery);
$registrationStmt->bindParam(':userId', $loggedInUserId);
$registrationStmt->execute();
$registeredSeats = $registrationStmt->fetchAll(PDO::FETCH_ASSOC);

// Create an array to store registered seats for each event
$userRegisteredSeats = [];
foreach ($registeredSeats as $registration) {
    $userRegisteredSeats[$registration['event_id']][] = $registration['seat_number'];
}

// Fetch all registered seats for any user
$allRegisteredSeatsQuery = "SELECT event_id, seat_number FROM event_registrations";
$allRegisteredSeatsStmt = $con->prepare($allRegisteredSeatsQuery);
$allRegisteredSeatsStmt->execute();
$allRegisteredSeats = $allRegisteredSeatsStmt->fetchAll(PDO::FETCH_ASSOC);

// Create an array to store all registered seats for each event
$allRegisteredSeatsArray = [];
foreach ($allRegisteredSeats as $registration) {
    $allRegisteredSeatsArray[$registration['event_id']][] = $registration['seat_number'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Available Events</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> 
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
            background-color: #f8f9fa; /* Light background color */
        }
       
        h2 {
            color: #4b0082;
            margin-bottom:20px;
           

             /* Space below the heading */
        }
        .card {
            border: none; /* Remove card border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .card-title {
            color: #4b0082; /* Title color */
        }
        .btn-purple {
            background-color: #4b0082;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a1da8;
        }
        .btn-booked {
            background-color: #6c757d !important; /* Gray color for booked seats */
            color: white !important; /* Keep text white */
        }
        .btn-outline-primary {
            border-color: #4b0082; /* Outline color for available seats */
            color: #4b0082; /* Text color for available seats */
        }
        .btn-outline-primary:hover {
            background-color: #4b0082; /* Background on hover */
            color: white; /* Text color on hover */
        }
        .modal-header {
            background-color: #4b0082; /* Header background */
            color: white; /* Header text color */
        }
        .modal-footer {
            justify-content: center; /* Center the footer buttons */
        }

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
      font-size: 18px;
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

  




 
    </style>
</head>
<body>







  <div style="margin-bottom:300px;" class="project">

    <nav style="margin-bottom:300px;" class="navbar navbar-expand-lg">
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
            <a class="nav-link" href="job_vacancies.php">Pet Quiz</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="book_meetup.php">Pet Meetups</a>
          </li>
         

          


          <li class="nav-item">
            <a class="nav-link" href="job_vacancies.php">Waiting List</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="view_notices.php">Notices</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calender_userside.php">calender</a>
          </li>

          

           <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Lost/Found pets
            </a>

         <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="userside_pets.php">Lost Pets</a>
              <a class="dropdown-item" href="userside_pets_cats.php">Found Pets</a>
              

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

    <div class="container">
        <h2 style="font-size: 50px; padding-top: 120px;" class="text-center">Available Events with Limited Seats</h2>

        <?php foreach ($events as $event): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                <p><?php echo htmlspecialchars($event['event_description']); ?></p>
                <p>Total Seats: <?php echo $event['total_seats']; ?> | Available Seats: <?php echo $event['available_seats']; ?></p>

                <?php for ($seat = 1; $seat <= $event['total_seats']; $seat++): ?>
                    <?php
                    // Check if the seat is booked by any user
                    $isBooked = isset($allRegisteredSeatsArray[$event['id']]) && in_array($seat, $allRegisteredSeatsArray[$event['id']]);
                    // Check if the current user has already registered for this event
                    $isRegistered = isset($userRegisteredSeats[$event['id']]) && in_array($seat, $userRegisteredSeats[$event['id']]);
                    ?>
                    <button class="btn <?php echo $isBooked ? 'btn-booked' : 'btn-outline-primary'; ?> mb-2"
                            id="seatBtn-<?php echo $event['id'] . '-' . $seat; ?>"
                            <?php echo ($isBooked || $isRegistered) ? 'disabled' : 'onclick="openRegisterModal(' . $event['id'] . ', ' . $seat . ')"'; ?>>
                        Seat <?php echo $seat; ?>
                    </button>
                <?php endfor; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal for Registration -->
    <div id="registerModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="registerForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Register for Event</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="eventId" name="eventId">
                        <input type="hidden" id="seatNumber" name="seatNumber">
                        <div class="form-group">
                            <label for="userName">Your Name:</label>
                            <input type="text" class="form-control" id="userName" name="userName" value="<?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?>" readonly>
                        </div>
                        <p>Seat <span id="selectedSeat"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-purple">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRegisterModal(eventId, seatNumber) {
            document.getElementById('eventId').value = eventId;
            document.getElementById('seatNumber').value = seatNumber;
            document.getElementById('selectedSeat').textContent = seatNumber;
            $('#registerModal').modal('show');
        }

        // Handle form submission with AJAX
        document.getElementById('registerForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent default form submission

            const eventId = document.getElementById('eventId').value;
            const seatNumber = document.getElementById('seatNumber').value;

            // SweetAlert confirmation for registration
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to register for seat " + seatNumber + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4b0082',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, register!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send form data via AJAX
                    const formData = new FormData(this);
                    
                    fetch('register_event.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#4b0082'
                            });

                            // Disable and change color of the booked seat
                            const seatBtn = document.getElementById(`seatBtn-${eventId}-${seatNumber}`);
                            seatBtn.classList.remove('btn-outline-primary');
                            seatBtn.classList.add('btn-booked');
                            seatBtn.disabled = true;

                            // Close modal
                            $('#registerModal').modal('hide');
                        } else {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                                confirmButtonColor: '#4b0082'
                            });
                        }
                    });
                }
            });
        };
    </script>
</body>
</html>

