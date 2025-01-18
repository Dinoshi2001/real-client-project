<?php
session_start();
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();
$errors = [];
$success = '';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

// Display success or error messages from session
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Handle form submission for new pet details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_pet_id'])) {
        // Handle update existing pet
        $edit_pet_id = $_POST['edit_pet_id'];
        $pet_type = $_POST['pet_type'];
        $pet_brand = $_POST['pet_brand'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $imagePath = null;

        // Process image upload if selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $uploadPath = 'uploads/';
            $imageName = uniqid() . '_' . $image['name'];
            $imagePath = $uploadPath . $imageName;
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $errors[] = "Failed to upload image.";
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE lostfind_pets SET pet_type = ?, pet_brand = ?, description = ?, images = ?, status = ? WHERE pet_id = ? AND user_id = ?");
            $stmt->execute([$pet_type, $pet_brand, $description, $imagePath, $status, $edit_pet_id, $user_id]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "Pet details updated successfully.";
                // Redirect to avoid resubmission on refresh
                header('Location: manage_lostfound_pets.php');
                exit();
            } else {
                $errors[] = "Failed to update pet details.";
            }
        }
    } else {
        // Handle add new pet
        $pet_type = $_POST['pet_type'];
        $pet_brand = $_POST['pet_brand'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $imagePath = null;

        if (empty($pet_type) || empty($pet_brand) || empty($description) || empty($status)) {
            $errors[] = "All fields are required.";
        }

        // Process image upload if selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $uploadPath = 'uploads/';
            $imageName = uniqid() . '_' . $image['name'];
            $imagePath = $uploadPath . $imageName;
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $errors[] = "Failed to upload image.";
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO lostfind_pets (user_id, pet_type, pet_brand, description, images, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $pet_type, $pet_brand, $description, $imagePath, $status]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "Pet details added successfully.";
                // Redirect to avoid resubmission on refresh
                header('Location: manage_lostfound_pets.php');
                exit();
            } else {
                $errors[] = "Failed to add pet details.";
            }
        }
    }
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    $stmt = $pdo->prepare("DELETE FROM lostfind_pets WHERE pet_id = ? AND user_id = ?");
    $stmt->execute([$pet_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Pet details deleted successfully.";
        // Redirect to avoid resubmission on refresh
        header('Location: manage_lostfound_pets.php');
        exit();
    } else {
        $_SESSION['errors'] = "Failed to delete pet details.";
    }
}

// Fetch all lost and found pets
$stmt = $pdo->prepare("SELECT * FROM lostfind_pets WHERE user_id = ?");
$stmt->execute([$user_id]);
$lostfound_pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lost and Found Pets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
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



        /*nav bar*/
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

    /*footer*/

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


    <div style="padding-top:100px;">
        

    </div>

    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Report Lost or Found Pet</h2>

            <!-- Success message for adding a pet -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <!-- Error messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="pet_status">Choose:</label>
                <select class="form-control" id="pet_status" onchange="showForm(this.value)">
                    <option value="">Select...</option>
                    <option value="lost">Lost</option>
                    <option value="found">Found</option>
                </select>
            </div>

            <form id="petForm" method="post" action="manage_lostfound_pets.php" enctype="multipart/form-data"
                style="display:none;">
                <input type="hidden" id="status" name="status">
                <div class="form-group">
                    <label for="pet_type">Pet Type:</label>
                    <input type="text" class="form-control" id="pet_type" name="pet_type" required>
                </div>
                <div class="form-group">
                    <label for="pet_brand">Pet Breed:</label>
                    <input type="text" class="form-control" id="pet_brand" name="pet_brand" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Upload Image:</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
        </div>

        <div class="container mt-5">
            <h2 class="text-center">Manage Lost and Found Pets</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lostfound_pets as $pet): ?>
                            <tr data-id="<?php echo $pet['pet_id']; ?>">
                                <td><?php echo htmlspecialchars($pet['pet_type']); ?></td>
                                <td><?php echo htmlspecialchars($pet['pet_brand']); ?></td>
                                <td><?php echo htmlspecialchars($pet['description']); ?></td>
                                <td><?php echo htmlspecialchars($pet['status']); ?></td>
                                <td class="pet-image">
                                    <?php if (!empty($pet['images'])): ?>
                                        <img src="<?php echo htmlspecialchars($pet['images']); ?>" alt="Pet Image" width="100">
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $pet['pet_id']; ?>"
                                        data-toggle="modal" data-target="#editModal">Edit</button>

                                    <form action="delete_lostfound_pet.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Pet Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Pet Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editPetForm" method="post" action="manage_lostfound_pets.php"
                            enctype="multipart/form-data">
                            <input type="hidden" id="edit_pet_id" name="edit_pet_id">
                            <input type="hidden" id="edit_status" name="status">
                            <div class="form-group">
                                <label for="edit_pet_type">Pet Type:</label>
                                <input type="text" class="form-control" id="edit_pet_type" name="pet_type" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_pet_brand">Pet Breed:</label>
                                <input type="text" class="form-control" id="edit_pet_brand" name="pet_brand" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">Description:</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="4"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_image">Upload Image:</label>
                                <input type="file" class="form-control-file" id="edit_image" name="image"
                                    accept="image/*">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div><!--footer start-->+
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

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#petForm').on('submit', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to add this pet?')) {
                        this.submit();
                    }
                });

                $('.edit-btn').on('click', function () {
                    var petId = $(this).data('id');
                    $.get('get_lostfound_pet.php', { pet_id: petId }, function (data) {
                        if (data.success) {
                            $('#edit_pet_id').val(data.data.pet_id);
                            $('#edit_pet_type').val(data.data.pet_type);
                            $('#edit_pet_brand').val(data.data.pet_brand);
                            $('#edit_description').val(data.data.description);
                            $('#edit_status').val(data.data.status);
                        } else {
                            alert(data.message);
                        }
                    }, 'json');
                });

                $('#editPetForm').on('submit', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to save changes to this pet?')) {
                        this.submit();
                    }
                });

                $('.delete-btn').on('click', function (e) {
                    if (!confirm('Are you sure you want to delete this pet?')) {
                        e.preventDefault();
                    }
                });
            });

            function showForm(status) {
                if (status) {
                    $('#status').val(status);
                    $('#petForm').show();
                } else {
                    $('#petForm').hide();
                }
            }
        </script>
    </div>
</body>

</html>
