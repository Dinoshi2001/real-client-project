<?php
session_start();
require_once 'classes/DbConnector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userid = $_POST['userid'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $contact_number = $_POST['contact_number'];
  $address = $_POST['address'];
  $dob = $_POST['dob'];
  $age = $_POST['age'];
  $nic = $_POST['nic'];
  $gender = $_POST['gender'];

  try {
    $conn = classes\DbConnector::getConnection();

    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, contact_number = :contact_number, 
                address = :address, dob = :dob, age = :age, nic = :nic, gender = :gender WHERE id = :userid";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':nic', $nic);
    $stmt->bindParam(':gender', $gender);

    if ($stmt->execute()) {
      // Set success message in session
      $_SESSION['success_message'] = 'Profile updated successfully!';
      header('Location: profile.php'); // Redirect back to the profile page
      exit();
    } else {
      echo "Error updating record.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

  $conn = null;
}
?>




<?php
require_once 'classes/DbConnector.php'; // Adjust the path as per your directory structure
require_once 'classes/Pet.php'; // Adjust the path as per your directory structure

use classes\Pet;

// Fetch pets data
$pets = Pet::getAllPets();

// Filter pets by type (e.g., dogs)
$filteredPets = array_filter($pets, function ($pet) {
  return strtolower($pet->pet_type) === 'bird' && strtolower($pet->status) === 'stock';
});
?>




<?php
// Check if user is logged in
if (isset($_SESSION['user'])) {
  // Get user data from session
  $user = $_SESSION['user'];
?>



<?php } else {
  // If user is not logged in, display a message or redirect to login page
  echo "<p></p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Profile</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    /* Custom styles */
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
    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family:'Comfortaa', cursive;
    }

    .background-container {
      background-image: url('images/bird cover.jpg');
      /* Replace with your image path */
      height: 500px;
      /* Full height */
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .overlay-text {
      color: white;
      font-size: 3rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
      text-align: center;
    }

    body {
      background-color: #f8f9fa;
      font-style: italic;
    }

    .card {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card img {
      height: 250px;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .clean-search-group {
      display: flex;
      width: 100%;
      border-radius: 25px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    .clean-search-box {
      flex: 1;
      border: none;
      padding: 0.75rem 1rem;
      border-radius: 25px 0 0 25px;
      font-size: 1rem;
      outline: none;
      box-shadow: none;
    }

    .clean-search-box:focus {
      outline: none;
      box-shadow: none;
    }

    .clean-search-box::placeholder {
      color: #999;
      font-size: 1rem;
    }

    .clean-search-button {
      border: none;
      background-color: #6c757d;
      color: white;
      padding: 0.75rem 1rem;
      border-radius: 0 25px 25px 0;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .clean-search-button:hover {
      background-color: #5a6268;
    }

    .clean-search-button i {
      font-size: 1rem;
    }

    @media (max-width: 767px) {
      .clean-search-box {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
      }

      .clean-search-button {
        padding: 0.5rem 0.75rem;
      }

      .clean-search-button i {
        font-size: 0.9rem;
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



    <!--pop up end-->
    <div class="background-container">
      <div style="font-weight: bolder;" class="overlay-text">
        Find Your Favourite Pet
      </div>
    </div>

    <!--search bar-->
    <div class="container mt-5">
      <div class="row justify-content-center mb-4">
        <div class="col-md-10">
          <form class="form-inline my-2 my-lg-0 w-100" id="searchForm">
            <div class="input-group clean-search-group w-100">
              <input type="text" class="form-control clean-search-box" placeholder="Search for your perfect pet..." aria-label="Search" id="searchInput">
              <div class="input-group-append">
                <button style="background-color:#6F099E;" class="btn clean-search-button" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--search bar end-->

    <div style="margin-bottom: 100px;" class="container mt-5">
      <div class="row" id="petCards">
        <?php foreach ($filteredPets as $pet) : ?>
          <div class="col-md-4 pet-card" data-pet-brand="<?php echo htmlspecialchars($pet->brand); ?>">
            <div style="margin-bottom:50px;" class="card">
              <?php $imagePath = 'uploads/' . $pet->image; ?>
              <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="Image of <?php echo htmlspecialchars($pet->pet_type); ?>">
              <div class="card-body">
                <h4 class="card-title"><?php echo htmlspecialchars($pet->pet_type); ?></h4>
                <h5><?php echo htmlspecialchars($pet->brand); ?></h5>
                <h6><?php echo htmlspecialchars($pet->price); ?></h6>
                <a href="pet_profile.php?id=<?php echo $pet->id; ?>" style="background-color: #4C056C;" class="btn btn-primary">More Details</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
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
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    document.getElementById('searchInput').addEventListener('input', function() {
      var searchValue = this.value.toLowerCase();
      var petCards = document.querySelectorAll('.pet-card');

      petCards.forEach(function(card) {
        var petBrand = card.getAttribute('data-pet-brand').toLowerCase();
        if (petBrand.startsWith(searchValue)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });

    // Optional: prevent form submission if not needed
    document.getElementById('searchForm').addEventListener('submit', function(event) {
      event.preventDefault();
    });
  </script>
</body>

</html>