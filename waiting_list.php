<?php
// Include required classes
include 'classes/DbConnector.php';
include 'classes/Pet.php';
include 'classes/User.php';

use classes\DbConnector;
use classes\Pet;
use classes\User;

// Start the session
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_form.php");
    exit;
}

$user = $_SESSION['user']; // User details from session

if (!isset($user['id'])) {
    die("User ID is not set in session. Please log in again.");
}

// Get the database connection
$conn = DbConnector::getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $petType = $_POST['petType'];
    $petBrand = $_POST['petBrand'];

    // Validate and sanitize input
    $petType = htmlspecialchars($petType);
    $petBrand = htmlspecialchars($petBrand);

    // Check if the pet is available in the stock
    $petQuery = $conn->prepare("SELECT status FROM pets WHERE pet_type = :pet_type AND brand = :brand AND status = 'stock' LIMIT 1");
    $petQuery->bindParam(':pet_type', $petType);
    $petQuery->bindParam(':brand', $petBrand);
    $petQuery->execute();

    $isInStock = false;
    if ($petQuery->rowCount() > 0) {
        $isInStock = true;
    }

    // Return JSON response based on stock status
    if ($isInStock) {
        $message = "The '$petBrand' is already in the shop.";
        echo json_encode(['message' => $message, 'inStock' => true]);
    } else {
        $message = "Do you want to add to the waiting list?";
        echo json_encode(['message' => $message, 'inStock' => false]);
    }
    exit;
}
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
    <style>
       
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

.container {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;

    width: 500px;
}

.header-image {
    width: 100%;
    border-radius: 8px 8px 0 0;
}

h1 {
    color: #4b0082;
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #4b0082;
}

select, input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 16px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4b0082;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #3a006f;
}

.message {
    margin-top: 20px;
    color: #4b0082;
    text-align: center;
}

/* Footer */
.footer {
    background-color: #5B1D76;
    color: white;
    padding: 20px 0;
    text-align: center;
    width: 100vw; /* Full width of the screen */
    position: relative;
    left: 0;
}

.footer-content {
    max-width: 1200px; /* Restricting the content to be centered */
    margin: 0 auto; /* Center content horizontally */
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

</div>




<div style="padding-top:150px;"></div>


  <div  class="container">
        <img src="images/waiting.webp" alt="Header Image" class="header-image"><br>
        <h1>Add to Waiting List</h1>
        <form  id="waitingListForm" method="POST" action="">
            <label for="petType">Pet Type:</label>
            <select id="petType" name="petType" required>
                <option value="">Select a pet type</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="bird">Bird</option>
                <option value="rabbit">Rabbit</option>
            </select>

            <label for="petBrand">Pet Brand:</label>
            <input type="text" id="petBrand" name="petBrand" required>

            <button type="submit">Add to Waiting List</button>
        </form>
    </div>

    <div class="message" id="message"></div>
    <!-- Footer Start -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2024 Best Pet Sellers</p>
            <ul class="footer-socials">
                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </footer>
    <!-- Footer End -->



</body>




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('waitingListForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.inStock) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Notice',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: data.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4b0082',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, add it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form again to actually add the user to the waiting list
                            fetch('add_to_waiting_list.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(resultData => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: resultData.message,
                                    confirmButtonText: 'OK'
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error); // Debugging: log the error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Something went wrong. Please try again later.',
                                    confirmButtonText: 'OK'
                                });
                            });
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error); // Debugging: log the error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>

 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      function travelPage(page) {
        window.location.href = page;
      }
    </script>
</body>
</html>



