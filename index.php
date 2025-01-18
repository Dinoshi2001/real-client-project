<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Selector Quiz</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
   <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navigation Bar</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <style type="text/css">
    

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




  <div style="padding-top: 100px;" class="container">
      <form action="result.php" method="POST" id="quizForm">
        <div class="section-title">Find Your Perfect Pet!</div>
        <div>
          <img src="images/quiz.jpg" alt="">
        </div>
        <h2 class="question">What type of pet do you want?</h2>
        <div class="options-container">
          <input type="hidden" name="pet_type" value="">
          <div class="option" onclick="selectOption(this)">Dog</div>
          <div class="option" onclick="selectOption(this)">Cat</div>
          <div class="option" onclick="selectOption(this)">Bird</div>
          <div class="option" onclick="selectOption(this)">Rabbit</div>
        </div>
        <div class="line"></div>
        <h2 class="question">What is your budget for buying a pet?</h2>
        <div class="options-container">
          <input type="hidden" name="pet_cost" value="">
          <div class="option" onclick="selectOption(this)">Less than Rs5000</div>
          <div class="option" onclick="selectOption(this)">Rs5000 - Rs10000</div>
          <div class="option" onclick="selectOption(this)">Rs10000 - Rs30000</div>
          <div class="option" onclick="selectOption(this)">More than Rs30000</div>
        </div>
        <div class="line"></div>
        <h2 class="question">How much can you spend on your pet each month?</h2>
        <div class="options-container">
          <input type="hidden" name="income_range" value="">
          <div class="option" onclick="selectOption(this)">Less than Rs5,000</div>
          <div class="option" onclick="selectOption(this)">Rs5,000 - Rs10,000</div>
          <div class="option" onclick="selectOption(this)">More than Rs10,000</div>
        </div>
        <div class="line"></div>
        <h2 class="question">How much time can you dedicate to pet care each day?</h2>
        <div class="options-container">
          <input type="hidden" name="time_dedication" value="">
          <div class="option" onclick="selectOption(this)">Less than 1 hour</div>
          <div class="option" onclick="selectOption(this)">1-4 hours</div>
          <div class="option" onclick="selectOption(this)">More than 4 hours</div>
        </div>
        <div class="line"></div>
        <h2 class="question">Do you have children at home?</h2>
        <div class="options-container">
          <input type="hidden" name="children" value="">
          <div class="option" onclick="selectOption(this)">Yes</div>
          <div class="option" onclick="selectOption(this)">No</div>
        </div>
        <div class="line"></div>
        <h2 class="question">What is your living situation?</h2>
        <div class="options-container">
          <input type="hidden" name="living_situation" value="">
          <div class="option" onclick="selectOption(this)">Apartment</div>
          <div class="option" onclick="selectOption(this)">House with a yard</div>
        </div>
        <button class="submit-btn" type="submit">Submit</button>
      </form>
  </div>

  <script>
    function selectOption(option) {
      const options = option.parentNode.querySelectorAll('.option');
      options.forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');
      // Set the value to the hidden input
      option.parentNode.querySelector('input[type=hidden]').value = option.textContent.trim();
    }
  </script>

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
