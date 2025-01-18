<?php
require 'dbConnector.php';
session_start();

use classes\User;

$db = new dbConnector();
$con = $db->getConnection();

if (!isset($_SESSION['user'])) {
    header('Location: login_form.php');
    exit();
}

$loggedInUserId = $_SESSION['user']['id'];
$message = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitPost'])) {
    savePetPost($con, $loggedInUserId);

    // Redirect to the same page to clear the form data
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

function savePetPost($con, $userId)
{
    global $message; // Use global variable to pass the message
    $postTitle = $_POST['postTitle'];
    $postContent = $_POST['postContent'];
    $imagePath = '';

    if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['postImage']['tmp_name'];
        $imageName = $_FILES['postImage']['name'];
        $imagePath = 'uploads/' . basename($imageName);

        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // Image uploaded successfully
        } else {
            $imagePath = ''; // Reset if image upload failed
        }
    }

    try {
        $query = "INSERT INTO pet_posts (user_id, post_title, post_content, post_image) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bindValue(1, $userId);
        $stmt->bindValue(2, $postTitle);
        $stmt->bindValue(3, $postContent);
        $stmt->bindValue(4, $imagePath);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Pet post saved successfully.";
            $_SESSION['message'] = $message;
        } else {
            $message = "Failed to save pet post.";
        }
    } catch (PDOException $exc) {
        $message = "An error occurred while saving the pet post.";
    }
}

function fetchPetPosts($con)
{
    try {
        $query = "SELECT p.*, u.first_name, u.last_name 
                  FROM pet_posts p 
                  JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exc) {
        die("Error: " . $exc->getMessage());
    }
}

$petPosts = fetchPetPosts($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
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
          
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
            padding-top: 100px;
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-title {
            font-size: 1.5em;
            color: #4b0082;
        }

        .card-subtitle {
            font-size: 1em;
            color: #555;
        }

        .card-text {
            font-size: 1em;
            color: #333;
        }

        .btn-purple {
            background-color: #4b0082;
            color: #fff;
        }

        .btn-purple:hover {
            background-color: #5c109a;
            color: #fff;
        }

        .card {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
            overflow: hidden;
        }

        .card img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 1.5em;
            color: #4b0082;
            margin-bottom: 10px;
        }

        .card-subtitle {
            font-size: 1em;
            color: #555;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1em;
            color: #333;
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


    <div class="container">
        <h2 class="text-center" style="color: #4b0082;">Share Your Pet Experiences</h2>
        <div class="card">
            <form id="petPostForm" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="postTitle">Post Title:</label>
                    <input type="text" class="form-control" id="postTitle" name="postTitle" required>
                </div>
                <div class="form-group">
                    <label for="postContent">Post Content:</label>
                    <textarea class="form-control" id="postContent" name="postContent" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="postImage">Post Image (Optional):</label>
                    <input type="file" class="form-control-file" id="postImage" name="postImage" accept="image/*">
                </div>
                <input type="hidden" id="userId" name="userId" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
                <button type="button" class="btn btn-purple" onclick="confirmSubmit()">Submit Post</button>
                <input type="hidden" name="submitPost" value="1">
                <!-- Hidden field to trigger the form submission in PHP -->
            </form>
        </div>

        <h2 class="mt-5 text-center" style="color: #4b0082;">Pet Posts</h2>
        <?php if (!empty($petPosts)): ?>
            <?php foreach ($petPosts as $post): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($post['post_title']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">by
                            <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?> on
                            <?php echo htmlspecialchars($post['created_at']); ?>
                        </h6>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                        <?php if (!empty($post['post_image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['post_image']); ?>" class="img-fluid" alt="Post Image">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No posts available.</p>
        <?php endif; ?>
    </div>


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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        function confirmSubmit() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit this post?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4b0082',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('petPostForm').submit();
                }
            });
        }

        // Show success message if available
        <?php if (isset($_SESSION['message'])): ?>
        Swal.fire({
            title: 'Success',
            text: '<?php echo $_SESSION['message']; unset($_SESSION['message']); ?>',
            icon: 'success',
            confirmButtonColor: '#4b0082'
        });
        <?php endif; ?>
    </script>
</body>

</html>
