<?php
require 'dbConnector.php';

$db = new dbConnector();
$pdo = $db->getConnection();

// Fetch all job vacancies
$stmt = $pdo->query("SELECT * FROM job_vacancies");
$vacancies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Vacancies</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /*@import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&display=swap');*/

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
            background-image: url('images/job3.jpeg'); /* Replace with your image path */
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

        .container {
            margin-top: 50px;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height: 200px;
        }

        .card-body {
            padding: 20px;
            height: 550px;
        }
        h2 {
            font-size: 2em;
            font-weight: bold;
            color: #4b0082;
            margin-bottom: 20px;
        }


        .btn-primary {
            background-color: #9370db;
            border-color: #9370db;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #6a0dad;
            border-color: #6a0dad;
        }

        .alert {
            margin-top: 20px;
        }

        .modal-header {
            background-color: #9370db;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
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




<div class="header-image">
        <h1>Discover Your Next Career Opportunity</h1>
    </div>
    <br>
    <div class="container">
       
        <div id="feedback" class="alert d-none"></div>
        <div class="row">
            <?php foreach ($vacancies as $vacancy): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($vacancy['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($vacancy['image']); ?>" class="card-img-top"
                                alt="Job Image">
                        <?php else: ?>
                            <img src="default-job-image.jpg" class="card-img-top" alt="Default Job Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($vacancy['title']); ?></h2>
                            <p class="card-text"><?php echo htmlspecialchars($vacancy['description']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($vacancy['location']); ?></p>
                            <p><strong>Salary:</strong> <?php echo htmlspecialchars($vacancy['salary']); ?></p>
                            <a href="#" class="btn btn-primary apply-btn" data-toggle="modal" data-target="#applyModal"
                                data-vacancy-id="<?php echo $vacancy['id']; ?>">Apply</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="applyForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="applyModalLabel">Apply for Job</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="vacancy_id" id="apply_vacancy_id">
                        <div class="form-group">
                            <label for="resume">Upload Resume</label>
                            <input type="file" class="form-control-file" id="resume" name="resume" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.apply-btn').click(function () {
                var vacancy_id = $(this).data('vacancy-id');
                $('#apply_vacancy_id').val(vacancy_id);
            });

            $('#applyForm').submit(function (event) {
                event.preventDefault(); // Prevent the default form submission

                var formData = new FormData(this);

                $.ajax
                    ({
                        url: 'apply_job.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            var data = JSON.parse(response);
                            var feedback = $('#feedback');
                            feedback.removeClass('d-none alert-success alert-danger').addClass('alert-' + data.messageType).text(data.message);
                            $('#applyModal').modal('hide');
                            if (data.messageType === 'success') {
                                feedback.addClass('alert-success').removeClass('alert-danger');
                            } else {
                                feedback.addClass('alert-danger').removeClass('alert-success');
                            }
                            setTimeout(function () {
                                feedback.addClass('d-none');
                            }, 5000);
                        }
                    });
            });
        });
    </script>