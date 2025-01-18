<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> <!-- Correct SweetAlert CSS -->

  <style>
    /* Custom styles */
    .navbar-nav {
      margin: 0 auto;
      display: flex;
      align-items: center;
    }

    .nav-link {
      margin-right: 20px;
      font-size: 18px;
      /* Change the size as needed */
    }

    .navbar-brand img {
      margin-right: 20px;
    }

    .card-login {
      max-width: 400px;
      margin: 30px auto;
      padding: 20px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-login h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .card-login .form-group {
      margin-bottom: 20px;
    }

    .card-login label {
      font-weight: bold;
    }

    .card-login input[type="text"],
    .card-login input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ced4da;
    }

    .card-login button {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: #6A208A;
      border: none;
      color: #fff;
      font-weight: bold;
    }

    .card-login button:hover {
      background-color: #0056b3;
    }

    .forgot-password {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body style=" font-family:'Comfortaa', cursive;">

  <div class="project">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#F3F1F5;">
      <a class="navbar-brand" href="#">
        <img style="width:130px; height:50px;" src="images/logo.png" alt="Your Shop" height="40">
      </a>
      <div class="ml-auto">
        <a class="navbar-brand" href="#">
          <button style="background-color:#6A208A" type="button" class="btn btn-primary">Sign In</button>
        </a>
      </div>
    </nav>
  </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <ul class="navbar-nav ml-auto"></ul>
    </ul>
  </div>

  <div class="container">
    <div class="card card-login">
      <div class="card-body">
        <h2 class="card-title">Login</h2>
        <form id="loginForm" action="login_process.php" method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button><br><br>



        </form>

        <a href="sign_in_form.php">
          <button type="submit" class="btn btn-primary">Sign In</button>
        </a>
        <div class="forgot-password">
          <a href="#">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
<br>
  <!--footer start-->
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
  <!--footer end-->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> <!-- Correct SweetAlert JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');

      if (status) {
        switch (status) {
          case '1':
            Swal.fire('Error', 'Please fill in all required fields.', 'error');
            break;
          case '2':
            Swal.fire('Error', 'Invalid email or password.', 'error');
            break;
          case '0':
          default:
            break;
        }
      }
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Use full jQuery, not slim version -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>