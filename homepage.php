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

    .diagonal-image-container {
      position: relative;
      width: 100%;
      height: 700px;
      background-image: linear-gradient(0deg, #5F0E85, white);
      overflow: hidden;
    }

    .pattern-back {
      position: absolute;
      /* z-index: 20; */
      width: 100%;
      bottom: 0%;
      opacity: 0.13;
      filter: invert(1);
    }

    .content-gif {
      position: absolute;
      z-index: 20;
      opacity: 1;
      left: 50%;
    }

    .diagonal-image {
      position: absolute;
      top: 0;
      right: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* background-image: url('images/Pattern-PNG-Transparent.png'); */
      /* Updated image URL */
      background-size: 100% 100%;
      /* Ensure the image covers the entire container */
      background-position: center;
      /* transform: skewY(-5deg); */
      /* Adjust the skew angle as needed */
      transform-origin: top left;
    }

    .wave {
      position: absolute;
      overflow: hidden;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100px;
      /* Adjust the height of the wave */
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path fill="%23fff" d="M0 0l120 24.2c120 24.2 360 72.6 600 60.8C960 72.6 1200 0 1200 0v120H0z"/></svg>') repeat-x;
      z-index: 20;
      background-color: rgba(0, 0, 0, 0);
    }


    .content {
      position: relative;
      z-index: 10;
      padding: 50px;
      color: #0c0b0b;
      /* background:linear-gradient(to right, hsla(90, 69%, 79%, 0.5) 50%, transparent 60%);;*/
      /* linear-gradient(to right, hsla(276, 69%, 79%, 0.5) 50%, transparent 60%);*/
      /* Set linear gradient background */
      overflow: hidden;
      /* Hide overflowing content */
      margin-top: 200px;
      margin-left: 40px;
      display: flex;
      align-items: center;
      align-self: center;
      flex-direction: column;
      /* Move content down to accommodate the diagonal shape */
    }


    .diagonal-section-new {
      position: relative;
    }

    .diagonal-background {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #022f18;
      /* Change the background color */
      transform-origin: top left;
      /*transform: skewY(-5deg);*/
      z-index: 1;
    }

    .content {
      position: relative;
      z-index: 2;
      padding: 20px;
      color: hwb(0 98% 2%);
      /* Change text color if needed */
    }

    .content h1 {
      text-shadow: 0px 5px 5px rgba(0, 0, 0, 0.6);
    }

    .btn-login,
    .btn-sign {
      background-color: white;
      border: 1px solid rgba(0, 0, 0, 0);
    }

    .btn-sign {
      background-color: #5B1D76;
      color: white;
    }

    .btn-login:hover {
      border: 1px solid #5B1D76;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
    }

    .btn-sign:hover {
      border: 1px solid rgba(255, 255, 255, 1);
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
      color: white;
    }

    .content-second {
      display: flex;
      width: 100%;
      gap: 50px;
      align-items: center;
      justify-content: center;
      padding-bottom: 30px;
      margin-top: 80px;
      margin-bottom: 50px;
    }

    .left-side {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 50%;
    }

    .left-side h2 {
      font-weight: bold;
      color: #5B1D76;
    }

    .left-side p {
      text-align: justify;
    }

    .cat-gif {
      width: 20%;
    }


    .btn-more {
      background-color: white;
      border: 1px solid #5B1D76;
    }

    .btn-more:hover {
      background-color: #5B1D76;
      color: white;
      border: 1px solid #5B1D76;
    }

    body {
      margin: 0;
      padding: 0;
    }

    .diagonal-bg-new {
      position: relative;
      background: linear-gradient(to bottom right, hsl(278, 61%, 80%), hsl(282, 90%, 31%));
      /* Adjust colors as needed */
      overflow: hidden;
      padding-bottom: 40px;
    }

    .diagonal-bg-new::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom right, hsl(278, 61%, 80%), hsl(282, 90%, 31%));
      /* Same gradient as background */
      transform-origin: top left;
      transform: skewY(-10deg);
    }

    .content-new {
      position: relative;
      z-index: 1;
      padding: 20px;
      color: #fff;
      text-align: center;
      margin-bottom: 10px;
    }

    .card {
      margin-bottom: 20px;
      border-radius: 20px;
      box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.3);
    }

    .card-container {
      display: flex;
      justify-content: space-around;
      /* Adjust as needed */
      align-items: center;
    }


    /* Pet Categories */

    .cat-card-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      padding: 20px 60px;
      gap: 20px;
      background-image: radial-gradient(circle at 50% 130%, #5B1D76, white, white);
    }

    .cat-card {
      display: grid;
      grid-template-columns: 1fr 1fr;
      background-color: white;
      width: 100%;
      border: 1px solid #5B1D76;
      border-radius: 20px;
      overflow: hidden;
      padding: 20px;
      cursor: pointer;
      box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.3);
      transition: 250ms;
    }

    .cat-side-img {
      width: 90%;
      border-radius: 40px;
      margin-left: auto;
    }

    .cat-card:hover {
      background-color: #5B1D76;
    }

    .cat-card:hover .cat-des {
      color: white;
    }

    .cat-card:hover .cat-side-img {
      box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.3);
    }




    /* Customer Card Container */

    .customer-card-container {
      display: flex;
      justify-content: space-around;
      gap: 20px;
      padding: 20px;
      padding-bottom: 150px;
      background-image: radial-gradient(circle at 100% 200%, #5B1D76, white, white);
    }

    .customer-card {
      background-color: white;
      border: 1px solid #5B1D76;
      border-radius: 15px;
      padding: 20px;
      width: 300px;
      display: flex;
      flex-direction: column;
      align-items: center;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease-in-out;
    }

    .customer-card:hover {
      transform: scale(1.01);
    }

    .customer-img img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
    }

    .customer-info {
      text-align: center;
    }

    .customer-info h3 {
      font-size: 1.5em;
      margin: 10px 0 5px;
    }

    .customer-title {
      background-color: #761D92;
      color: white;
      border-radius: 10px;
      padding: 5px 10px;
      margin: 0;
    }

    .customer-description {
      font-size: 1em;
      color: #333;
      margin-top: 10px;
    }









    /*contact form css*/

    .contact-container {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin: 50px 0;
      padding: 20px 100px;
      gap: 40px;
    }

    .contact-form {
      flex: 1;
      background-color: #efeaf1;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .contact-form h2 {
      margin-bottom: 20px;
      color: #5B1D76;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 15px;
      border: 1px solid #5B1D76;
      border-radius: 10px;
      font-size: 1em;
      outline: none;
    }

    .form-group textarea {
      height: 150px;
      resize: none;
    }

    .form-group button {
      width: max-content;
      padding: 10px 20px;
      background-color: #761D92;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .form-group button:hover {
      background-color: #5d176f;
    }

    .contact-info {
      flex: 1;
      text-align: center;
    }

    .contact-info img {
      width: 100%;
      max-width: 340px;
      height: auto;
    }

    .contact-info h2 {
      margin-bottom: 5px;
      color: #5B1D76;
      font-weight: bold;
    }

    .contact-info p {
      color: #555;
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


    /* Bot */

    .bot-icon {
      position: fixed;
      z-index: 100;
      width: 80px;
      border-radius: 50%;
      bottom: 50px;
      right: 50px;
      cursor: pointer;
      box-shadow: 0px 0px 5px #5b1d76, 0px 0px 15px #5b1d76, 0px 0px 35px #5b1d76;
      transition: 250ms;
      animation: pulse-shadow 1.5s infinite;
    }

    .bot-icon:hover {
      animation: shakeUp 1s infinite;
    }

    @keyframes pulse-shadow {

      0%,
      100% {
        box-shadow: 0 0 10px 5px rgba(91, 29, 118, 0.7);
      }

      50% {
        box-shadow: 0 0 20px 15px rgba(91, 29, 118, 0.4);
      }
    }

    @keyframes shakeUp {

      0%,
      60% {
        transform: translateY(0);
      }

      70% {
        transform: translateY(-2px);
      }

      80% {
        transform: translateY(2px);
      }

      90% {
        transform: translateY(-1px);
      }

      100% {
        transform: translateY(0);
      }
    }

    .modal .modal-dialog {
      max-width: 500px;
    }


    
  </style>
</head>

<!-- <body style=" font-family:'Comfortaa', cursive;"> -->

<body style=" font-family:'Comfortaa', cursive;">

  <?php
  // Check if user is logged in
  if (isset($_SESSION['user'])) {
    // Get user data from session
    echo "<img src='NOXDev/assets/chatbot.gif' alt='Chat-bot-icon' class='bot-icon' onclick=\"window.location.href='NOXDev/index.php'\">";
  }
  ?>



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





    <div class="diagonal-image-container">
      <div class="diagonal-image">
        <img src="images/Pattern-PNG-Transparent.png" alt="" class="pattern-back">
      </div>
      <div class="wave">
      </div>
      <div class="content">
        <!-- Your content goes here -->
        <h1 style="font-size:80px; ">PetPalooza</h1>
        <p style="font-size:20px;">The best pet sellers in Sri Lanka. joining with us to find your favorite pet.</p>
        <div class="buttons">
          <button class="btn btn-login" onclick="travelPage('login_form.php')">Login</button>
          <button class="btn btn-sign" onclick="travelPage('sign_in_form.php')">Sign Up</button>
        </div>
      </div>
    </div>

    <div class="content-second">
      <!-- Left side for text -->
      <div class="left-side">
        <h2>About Us</h2>
        <p>
          "Step into our pet paradise, where wagging tails and gentle purrs welcome you to a world of companionship and joy. At our pet shop, we understand that pets aren't just animals—they're cherished members of the family. That's why we go above and beyond to provide a diverse selection of pets, each brimming with personality and ready to steal your heart. From the energetic bounce of puppies to the graceful elegance of cats, from the melodious chirps of birds to the mesmerizing slither of reptiles, our shop is a haven for animal lovers of all kinds. Our commitment to responsible pet ownership means that each furry, feathered, or scaly friend receives the care and attention they deserve, from their first playful pounce to their golden years of companionship. Whether you're a seasoned pet parent or embarking on the exciting journey of pet ownership for the first time, our dedicated team is here to guide you every step of the way. Come experience the love and laughter that only a pet can bring—visit our shop today and discover the perfect companion to share life's adventures with."
        </p>
        <a href="pet_posts.php" class="btn btn-more">more details</a>
      </div>

      <img src="images/cat-playing.gif" alt="" class="cat-gif">
      <!-- Right side for image collection -->
      <!-- <div class="right-side">
        <div class="image-collection">
          <img src="images/image 01.jpg" alt="Image 1">
        </div>
      </div> -->

    </div>
    <script>
      // Ensure the modal loads with user data populated
      /*
      $(document).ready(function () {
          $('#profileModal').on('show.bs.modal', function (event) {
              var modal = $(this);
              var user = <?php echo json_encode($user); ?>;
              
              modal.find('#userid').val(user.id);
              modal.find('#username').val(user.first_name + ' ' + user.last_name);
              modal.find('#email').val(user.email);
              modal.find('#contactNumber').val(user.contact_number);
              modal.find('#address').val(user.address);
          });
      });*/
    </script>


    <!--cards-->


    <div class="diagonal-bg-new">
      <div class="container">
        <div class="content-new">
          <h2>PetPalooza services</h2>

        </div>
        <div class="row justify-content-center">
          <div class="col-md-3">
            <div style="height:200px;" class="card">
              <div class="card-body">
                <h5 class="card-title">Pet Transport and Relocation Services</h5>
                <img style="width:100px; height:100px;" src="images/icon1.jpg" class="rounded mx-auto d-block" alt="...">

              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div style="height:200px;" class="card">
              <div class="card-body">
                <h5 class="card-title">Pet Insurance Plans</h5>
                <img style="width:100px; height:100px;" src="images/icon 2.jpg" class="rounded mx-auto d-block" alt="...">


              </div>
            </div>
          </div>


          <div class="col-md-3">
            <div class="card">
              <div style="height:200px;" class="card-body">
                <h5 class="card-title">Pet Health Check-ups and Vaccinations</h5>
                <img style="width:100px; height:100px;" src="images/icon 3.png" class="rounded mx-auto d-block" alt="...">

              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card">
              <div style="height:200px;" class="card-body">
                <h5 class="card-title">Pet Training Programs</h5>
                <img style="width:100px; height:100px;" src="images/icon 4.webp" class="rounded mx-auto d-block" alt="...">

              </div>
            </div>
          </div>




        </div>
      </div>
    </div>

    <!--end cards-->


    <h2 style=" text-align: center;  padding-top: 50px; padding-bottom: 10px; font-weight:bold; color:#5B1D76;">Pet Categories</h2>


    <div class="cat-card-container">
      <div class="cat-card">
        <div class="cat-des">
          <h5 class="card-title">Dogs</h5>
          <p class="card-text">Dogs are loyal, affectionate, and intelligent companions, making them one of the most popular pets. They come in various breeds, sizes, and temperaments, suitable for different lifestyles and living situations.</p>
        </div>
        <img src="images/beagle.gif" class="cat-side-img" alt="Dogs">
      </div>

      <div class="cat-card">
        <div class="cat-des">
          <h5 class="card-title">Cats</h5>
          <p class="card-text">Cats are independent yet loving pets that are known for their playful and curious nature. They are low-maintenance compared to dogs, as they are generally self-cleaning and can entertain themselves for hours.</p>
        </div>
        <img src="images/cat-play.gif" class="cat-side-img" alt="Cats">
      </div>

      <div class="cat-card">
        <div class="cat-des">
          <h5 class="card-title">Birds</h5>
          <p class="card-text">Birds are vibrant, social pets that can bring a touch of nature into your home. They come in a wide range of species, from small, talkative parrots. Birds require mental stimulation and a clean environment to thrive.</p>
        </div>
        <img src="images/macaw.gif" class="cat-side-img" alt="Birds">
      </div>

      <div class="cat-card">
        <div class="cat-des">
          <h5 class="card-title">Rabbits</h5>
          <p class="card-text">Rabbits are gentle, quiet pets that enjoy companionship and gentle handling. They are social animals that thrive in pairs or groups and require a spacious enclosure with plenty of room to hop and play.</p>
        </div>
        <img src="images/rabbit.gif" class="cat-side-img" alt="Rabbits">
      </div>
    </div>



    <!--second card set end-->


    <!--comment section start-->

    <h2 style="text-align: center;  padding-top: 100px; padding-bottom: 10px; font-weight:bold; color:#5B1D76;">customers says</h2>

    <div class="customer-card-container">
      <div class="customer-card">
        <div class="customer-img">
          <img src="images/personImage2.png" alt="Profile Picture">
        </div>
        <div class="customer-info">
          <h3>Sarah Mitchell</h3>
          <p class="customer-title">Pets Educator</p>
          <p class="customer-description">Sarah is passionate about educating pet owners, helping them understand animal behavior and training techniques to ensure a happy and healthy environment for their pets.</p>
        </div>
      </div>

      <div class="customer-card">
        <div class="customer-img">
          <img src="images/personImage3.png" alt="Profile Picture">
        </div>
        <div class="customer-info">
          <h3>Michael Thompson</h3>
          <p class="customer-title">Pet Enthusiast</p>
          <p class="customer-description">Michael has been a pet enthusiast for over a decade, sharing his knowledge on different pet breeds and their unique needs to help others care for their furry companions.</p>
        </div>
      </div>

      <div class="customer-card">
        <div class="customer-img">
          <img src="images/personImage2.png" alt="Profile Picture">
        </div>
        <div class="customer-info">
          <h3>Emily Rodriguez</h3>
          <p class="customer-title">Pet Veterinarian</p>
          <p class="customer-description">As a certified veterinarian, Emily provides expert medical care and guidance for pets, ensuring they receive the best treatment and attention for their well-being.</p>
        </div>
      </div>

    </div>


   




    <!--contact form start-->

    <div class="contact-container">
      <div class="contact-form">
        <h2>Contact Us</h2>
        <form action="contact_process.php" method="post">
          <div class="form-group">
            <input type="text" placeholder="Name" name="name" required>
          </div>
          <div class="form-group">
            <input type="email" placeholder="Email" name="email" required>
          </div>
          <div class="form-group">
            <textarea placeholder="Message" name="message" required></textarea>
          </div>
          <div class="form-group">
            <button type="submit">Send</button>
          </div>
        </form>
      </div>

      <div class="contact-info">
        <img src="images/contact_us.avif" alt="Contact Us">
        <h2>Contact Information</h2>
        <p>123 Street, City, Country</p>
        <p>example@example.com</p>
        <p>+1234567890</p>
      </div>
    </div>



    <!--contact form end-->

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
      // Add event listener to the form submit event
      document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting immediately

        // Display the SweetAlert confirmation dialog
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to save the changes?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        }).then((result) => {
          if (result.isConfirmed) {
            // If confirmed, submit the form
            e.target.submit();
          }
        });
      });

      <?php if (isset($_SESSION['success_message'])) : ?>
        Swal.fire({
          title: 'Success!',
          text: '<?php echo $_SESSION['success_message']; ?>',
          icon: 'success',
          confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success_message']); // Clear the message after displaying 
        ?>
      <?php endif; ?>
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