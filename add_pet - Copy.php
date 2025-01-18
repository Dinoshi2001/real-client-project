<?php
session_start(); // Start the session

require 'dbConnector.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_form.php");
    exit;
}

$user = $_SESSION['user']; // User details from session

if (!isset($user['id'])) {
    die("User ID is not set in session. Please log in again.");
}



$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new instance of the dbConnector class
    $db = new dbConnector();
    $pdo = $db->getConnection();

    // Collect form data
    $sellerName = $_POST['sellerName'];
    $sellerAddress = $_POST['sellerAddress'];
    $sellerContact = $_POST['sellerContact'];
    $petType = $_POST['petType'];
    $petName = $_POST['petName'];
    $petAge = $_POST['petAge'];
    $petDescription = $_POST['petDescription'];
    $petPrice = $_POST['petPrice'];

    // Handle file uploads
    $target_dir = "uploads/";
    $photo_paths = [];

    foreach ($_FILES['petPhotos']['name'] as $key => $photo_name) {
        $target_file = $target_dir . basename($photo_name);
        if (move_uploaded_file($_FILES['petPhotos']['tmp_name'][$key], $target_file)) {
            $photo_paths[] = $target_file;
        }
    }
    $petPhotos = implode(",", $photo_paths);

    // Insert data into database using prepared statements
    $sql = "INSERT INTO seller_pets (seller_name, seller_address, seller_contact, pet_type, pet_name, pet_age, pet_description, pet_price, pet_photos) 
            VALUES (:seller_name, :seller_address, :seller_contact, :pet_type, :pet_name, :pet_age, :pet_description, :pet_price, :pet_photos)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':seller_name' => $sellerName,
            ':seller_address' => $sellerAddress,
            ':seller_contact' => $sellerContact,
            ':pet_type' => $petType,
            ':pet_name' => $petName,
            ':pet_age' => $petAge,
            ':pet_description' => $petDescription,
            ':pet_price' => $petPrice,
            ':pet_photos' => $petPhotos,
        ]);
        $message = "Pet details added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Get user data from session
$sellerName = '';
$sellerAddress = '';
$sellerContact = '';
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $sellerName = $user['first_name'] . ' ' . $user['last_name'];
    $sellerAddress = $user['address'];
    $sellerContact = $user['contact_number'];
}

// Fetch pet details from the database
$db = new dbConnector();
$pdo = $db->getConnection();
$sql = "SELECT * FROM seller_pets WHERE seller_name = :seller_name";
$stmt = $pdo->prepare($sql);
$stmt->execute([':seller_name' => $sellerName]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Pet</title>




  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
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

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #4b0082;
            /* Dark Purple */
        }

        .btn-primary {
            background-color: #9370db;
            /* Light Purple */
            border-color: #9370db;
        }

        .btn-primary:hover {
            background-color: #6a0dad;
            /* Dark Purple */
            border-color: #6a0dad;
        }

        .form-control:focus {
            border-color: #9370db;
            box-shadow: 0 0 0 0.2rem rgba(147, 112, 219, 0.25);
        }

        .form-group label {
            color: #4b0082;
            /* Dark Purple */
        }

        .alert-success {
            background-color: #88b04b;
            border-color: #88b04b;
            color: white;
        }

        .card-container {
            margin-top: 50px;
        }

        .card {
            margin: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card img {
            max-height: 200px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            background-color: #ffffff;
            padding: 15px;
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

        .modal-content {
            font-family: 'Comfortaa', cursive;
        }

        @media (max-width: 768px) {
            .header-image h1 {
                font-size: 2em;
                padding: 5px 10px;
            }

            .form-container {
                padding: 20px;
            }


             /* Footer */

     .card img {
                height: 150px;
            }
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

    <div class="header-image">
        <h1>Find a Loving Home for Your Pet</h1>
    </div>
    <br><br>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Sell Your Pet</h2>
            <?php if ($message): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form id="petForm" method="post" action="add_pet.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="sellerName">Seller Name</label>
                    <input type="text" class="form-control" id="sellerName" name="sellerName" value="<?php echo htmlspecialchars($sellerName); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sellerAddress">Address</label>
                    <textarea class="form-control" id="sellerAddress" name="sellerAddress" rows="2" required><?php echo htmlspecialchars($sellerAddress); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="sellerContact">Contact Number</label>
                    <input type="text" class="form-control" id="sellerContact" name="sellerContact" value="<?php echo htmlspecialchars($sellerContact); ?>" required>
                </div>
                <div class="form-group">
                    <label for="petType">Pet Type</label>
                    <select class="form-control" id="petType" name="petType" required>
                        <option value="">Select Pet Type</option>
                        <option value="Puppy">Puppy</option>
                        <option value="Cat">Cat</option>
                        <option value="Bird">Bird</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="petName">Pet Name</label>
                    <input type="text" class="form-control" id="petName" name="petName" required>
                </div>
                <div class="form-group">
                    <label for="petAge">Pet Age (in months)</label>
                    <input type="number" class="form-control" id="petAge" name="petAge" required>
                </div>
                <div class="form-group">
                    <label for="petDescription">Description</label>
                    <textarea class="form-control" id="petDescription" name="petDescription" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="petPrice">Price</label>
                    <input type="number" class="form-control" id="petPrice" name="petPrice" required>
                </div>
                <div class="form-group">
                    <label for="petPhotos">Upload Photos</label>
                    <input type="file" class="form-control-file" id="petPhotos" name="petPhotos[]" multiple required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
        </div>

        <div class="card-container">
            <h2 class="text-center">Your Pets</h2>
            <div class="row">
                <?php foreach ($pets as $pet): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php
                            $photos = explode(",", $pet['pet_photos']);
                            echo '<img src="' . htmlspecialchars($photos[0]) . '" alt="Pet Photo">';
                            ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($pet['pet_name']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($pet['pet_type']); ?></h6>
                                <p class="card-text">Age: <?php echo htmlspecialchars($pet['pet_age']); ?> months</p>
                                <p class="card-text"><?php echo htmlspecialchars($pet['pet_description']); ?></p>
                                <p class="card-text">Price: $<?php echo htmlspecialchars($pet['pet_price']); ?></p>
                                <button class="btn btn-primary edit-btn" data-id="<?php echo $pet['id']; ?>" data-toggle="modal" data-target="#editModal">Edit</button>
                                <a href="delete_sell_pet.php?id=<?php echo $pet['id']; ?>" class="btn btn-danger">Delete</a>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Pet Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPetForm" method="post" action="edit_pet.php" enctype="multipart/form-data">
                        <input type="hidden" id="editPetId" name="petId">
                        <div class="form-group">
                            <label for="editPetType">Pet Type</label>
                            <select class="form-control" id="editPetType" name="petType" required>
                                <option value="Puppy">Puppy</option>
                                <option value="Cat">Cat</option>
                                <option value="Bird">Bird</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPetName">Pet Name</label>
                            <input type="text" class="form-control" id="editPetName" name="petName" required>
                        </div>
                        <div class="form-group">
                            <label for="editPetAge">Pet Age (in months)</label>
                            <input type="number" class="form-control" id="editPetAge" name="petAge" required>
                        </div>
                        <div class="form-group">
                            <label for="editPetDescription">Description</label>
                            <textarea class="form-control" id="editPetDescription" name="petDescription" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editPetPrice">Price</label>
                            <input type="number" class="form-control" id="editPetPrice" name="petPrice" required>
                        </div>
                        <div class="form-group">
                            <label for="editPetPhotos">Upload Photos</label>
                            <input type="file" class="form-control-file" id="editPetPhotos" name="petPhotos[]" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

      <!--footer start-->
  <!--footer start-->+
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


    <script>
        $(document).ready(function () {
            $('.edit-btn').on('click', function () {
                var petId = $(this).data('id');
                $.ajax({
                    url: 'get_pet_details.php',
                    method: 'GET',
                    data: { id: petId },
                    success: function (data) {
                        var pet = JSON.parse(data);
                        $('#editPetId').val(pet.id);
                        $('#editPetType').val(pet.pet_type);
                        $('#editPetName').val(pet.pet_name);
                        $('#editPetAge').val(pet.pet_age);
                        $('#editPetDescription').val(pet.pet_description);
                        $('#editPetPrice').val(pet.pet_price);
                    }
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





