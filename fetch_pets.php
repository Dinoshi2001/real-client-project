<?php
require 'dbConnector.php';

// Create a new instance of the dbConnector class
$db = new DbConnector();
$pdo = $db->getConnection();

// Fetch pet data
$sql = "SELECT * FROM seller_pets";
try {
    $stmt = $pdo->query($sql);
    $pets = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Pets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
        body {
    overflow-x: hidden;
    height: 100vh;
    margin: 0;
    display: flex;
     font-family:'Comfortaa', cursive;
}

#wrapper {
    display: flex;
    width: 100%;
}

#sidebar-wrapper {
    height: 100vh;
    overflow-y: auto;
    width: 250px;
    flex-shrink: 0;
    background-color: #4C056C; /* Change this color to your desired background color */
    color: #ffffff; /* Ensures text is readable on the new background */
}

.profile-icon {
    width: 50px; /* Adjust the size as needed */
    height: 50px; /* Adjust the size as needed */
    border-radius: 50%; /* Makes the icon circular */
    margin-right: 10px; /* Space between the icon and text */
    vertical-align: middle; /* Aligns the icon with the text */
}

/* Optional: Add styles to remove underline from the link and ensure the icon is inline */
.sidebar-heading a {
    text-decoration: none;
    display: inline-block;
}

#page-content-wrapper {
    width: 100%;
    overflow-y: auto;
    padding: 20px;
}

.sidebar-heading {
    padding: 10px 15px;
    font-size: 1.25rem;
    color: #ffffff; /* Ensures text color is readable on the new background */
}

.list-group-item {
    border: none;
    padding: 15px 20px;
    background-color: #4C056C; /* Matches the sidebar background color */
    color: #ffffff; /* Ensures text color is readable */
}

.list-group-item:hover {
    color: white;
    background-color: #670D8F; /* Slightly lighter color for hover effect */
}

.bg-primary, .bg-success, .bg-warning, .bg-danger {
    padding: 20px;
    border-radius: 5px;
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

        .container {
            color: #4b0082;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            margin: 15px;
            flex: 0 0 300px;
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

        .price {
            font-size: 1.2em;
            color: #777;
        }

        .card-footer {
            background-color: #f9f9f9;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
        }

        .card-footer button {
            background-color: #9370db;
            border: none;
            padding: 10px 15px;
            color: white;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .card-footer button:hover {
            background-color: #6a0dad;
        }
    </style>
</head>

<body>

    <div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <a href="profile_page.html">
            <img style="margin-left: 60px; margin-bottom:20px; margin-top: 20px;" src="images/profile.jpg" alt="Profile Icon" class="profile-icon">
        </a><br>
        
    </div>
     <div class="list-group list-group-flush">
        <a style="font-size:20px;" href="admin_dashboard.php" class="list-group-item list-group-item-action">Admin Dashboard</a>
            <a href="admin_dashboard_view_users.php" class="list-group-item list-group-item-action">User Details</a>
             <a href="admin_dashboard_add_pets.php" class="list-group-item list-group-item-action">Add Pet Details</a>
              <a href="admin_dashboard_view_pets.php" class="list-group-item list-group-item-action">View Pet Details</a>
               <a href="pet_transaction.php" class="list-group-item list-group-item-action">Pet Transactions</a>
               <a href="fetch_pets.php" class="list-group-item list-group-item-action">Buy Pets</a>
               <a href="view_bookings.php" class="list-group-item list-group-item-action">Pet Meetings</a>
                  <a href="waiting_list_admin.php" class="list-group-item list-group-item-action">Waiting List</a>

                   <a href="admin_events.php" class="list-group-item list-group-item-action">Add Events</a>
                    <a href="admin_retrieve_events.php" class="list-group-item list-group-item-action">Seat booking</a>
                <a href="manage_pet_stories.php" class="list-group-item list-group-item-action">Pet of the Day</a>

            <a href="calender.php" class="list-group-item list-group-item-action">Calendar</a>
            
           
            <a href="notices.php" class="list-group-item list-group-item-action">Notices</a>


            <a href="admin_lostfound_pets.php" class="list-group-item list-group-item-action">Lost/Found Pets</a>
            
          
             <a href="manage_vacancies.php" class="list-group-item list-group-item-action">Manage Vacancies</a>
             <a href="admin_job_applications.php" class="list-group-item list-group-item-action">Job Applications</a>

            <a href="admin_view_posts.php" class="list-group-item list-group-item-action">Pet Experiences</a>
                
                  
                   
               
        </div>
    </div>

        
    </div>
</div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                
            </nav>

    <div class="container">
        <h1 class="text-center">Admin - View Pets</h1>
        <div class="card-container">
            <?php foreach ($pets as $pet): ?>
                <div class="card">
                    <?php
                    $photos = explode(',', $pet['pet_photos']);
                    ?>
                    <img src="<?php echo $photos[0]; ?>" class="card-img-top" alt="Pet Photo">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($pet['pet_name']); ?></h5>
                        <p class="card-text">Type: <?php echo htmlspecialchars($pet['pet_type']); ?></p>
                        <p class="card-text">Age: <?php echo htmlspecialchars($pet['pet_age']); ?> months</p>
                        <p class="card-text">Description: <?php echo htmlspecialchars($pet['pet_description']); ?></p>
                        <p class="price">$<?php echo htmlspecialchars($pet['pet_price']); ?></p>
                    </div>
                    <div class="card-footer">
                        <button class="contact-btn" data-toggle="modal" data-target="#contactModal"
                            data-name="<?php echo htmlspecialchars($pet['seller_name']); ?>"
                            data-contact="<?php echo htmlspecialchars($pet['seller_contact']); ?>"
                            data-address="<?php echo htmlspecialchars($pet['seller_address']); ?>">Contact Seller</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Contact Seller</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalName"></p>
                    <p id="modalContact"></p>
                    <p id="modalAddress"></p>
                </div>
                <div class="modal-footer">
                    <a href="#" id="whatsappLink" class="btn btn-success">Contact via WhatsApp</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#contactModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var name = button.data('name');
                var contact = button.data('contact');
                var address = button.data('address');

                var modal = $(this);
                modal.find('#modalName').text('Name: ' + name);
                modal.find('#modalContact').text('Contact: ' + contact);
                modal.find('#modalAddress').text('Address: ' + address);

                // Set WhatsApp link
                var whatsappLink = 'https://wa.me/' + contact;
                modal.find('#whatsappLink').attr('href', whatsappLink);
            });
        });
    </script>
</body>

</html>