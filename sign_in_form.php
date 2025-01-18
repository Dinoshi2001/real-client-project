<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Custom styles */
    .navbar-nav {
      margin: 0 auto;
      display: flex;
      align-items: center;
    }

    .nav-link {
      margin-right: 20px;
      font-size: 18px; /* Change the size as needed */
    }

    .navbar-brand img {
      margin-right: 20px;
    }

    .navbar-nav {
      margin: 0 auto;
      display: flex;
      align-items: center;
    }

    .nav-link {
      margin-right: 20px;
      font-size: 18px;
      color: #5F0E85;
    }

    .nav-link:hover {
      background-color: #5F0E85;
      color: white;
    }

    .navbar-brand img {
      margin-right: 20px;
    }


    .form-container {
      max-width: 500px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      font-weight: bold;
    }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="date"],
    .form-group input[type="number"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ced4da;
    }
    .form-group input[type="radio"] {
      margin-right: 10px;
    }
    .form-group .gender-label {
      margin-right: 20px;
      font-weight: normal;
    }
    .form-group .error-message {
      color: #dc3545;
      font-size: 14px;
    }
    .submit-btn {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: #59077F;
      border: none;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body style=" font-family:'Comfortaa', cursive;">

<div class="project">
      <nav class="navbar navbar-expand-lg  bg-light">
      <a class="navbar-brand" href="#">
        <img src="images/logo.png" alt="Your Shop" height="40" width="100">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="homepage.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">special pets</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              pets
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="userside_pets.php">Dogs</a>
              <a class="dropdown-item" href="userside_pets_cats.php">Cats</a>
              <a class="dropdown-item" href="userside_pets_birds.php">Birds</a>
              <a class="dropdown-item" href="userside_pets_birds.php">Rabbits</a>

            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Notices</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="calender_userside.php">calender</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pet quiz</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Sell your pets</a>
          </li>

        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-bell fa-lg"></i></a>
          </li>



          <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal"><i class="fas fa-user fa-lg"></i></a>
          </li>

          <li class="nav-item">
          <a style="background-color:#5E0B6C; color: white;" class="nav-link" href="logout.php">Logout</a>
        </li>
        </ul>
      </div>
    </nav>
</div>

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav">
    <ul class="navbar-nav ml-auto">
    </ul>
  </ul>
</div>


<?php
// Check for status parameter in the URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    switch ($status) {
        case 0:
            // No form submission
            break;
        case 1:
            // Please fill in all required fields.
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Please fill in all required fields.",
                });
            </script>';
            break;
        case 2:
            // User registered successfully!
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "User registered successfully!",
                });
            </script>';
            break;
        case 3:
            // There was an error registering the user.
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "There was an error registering the user.",
                });
            </script>';
            break;
        case 4:
            // Password should be at least 8 characters long.
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Password should be at least 8 characters long.",
                });
            </script>';
            break;
        case 5:
            // Password complexity requirements not met.
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Password should contain at least one uppercase letter, one lowercase letter, one number, and one special character.",
                });
            </script>';
            break;
        case 6:
            // Passwords do not match.
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Passwords do not match.",
                });
            </script>';
            break;
        case 7:
            // Invalid date of birth.
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Invalid date of birth.",
                });
            </script>';
            break;
    }
}
?>


<div class="container">
  <div class="form-container">
    <h2>Sign Up</h2>
    <form id="registrationForm" action="signin_process.php" method="POST">
      <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first-name" required>
      </div>
      <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last-name" required>
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
      </div>
      <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" required>
      </div>
      <div class="form-group">
        <label for="age">Age</label>
        <input type="number" id="age" name="age" min="1" required>
      </div>
      <div class="form-group">
        <label for="nic">NIC Number</label>
        <input type="text" id="nic" name="nic" required>
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="contact-number">Contact Number</label>
        <input type="tel" id="contact-number" name="contact-number" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Re-enter Password</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
        <span id="password-error" class="error-message" style="display: none;">Passwords do not match</span>
      </div>
      <div class="form-group">
        <label>Gender</label><br>
        <label class="gender-label">
          <input type="radio" name="gender" value="male" required> Male
        </label>
        <label class="gender-label">
          <input type="radio" name="gender" value="female"> Female
        </label>
      </div>
      <button type="submit" class="submit-btn">Sign Up</button>
    </form>
  </div>
</div>

<footer style="background-color: #5B1D76;" class="footer mt-auto py-3 text-white">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <p style="color:white;">© 2024 Best Pet Sellers</p>
      </div>
      <div style="font-size:30px;" class="col-md-6 text-md-right">
        <ul class="list-inline mb-0">
          <li class="list-inline-item">
            <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-white"><i class="fab fa-whatsapp"></i></a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
