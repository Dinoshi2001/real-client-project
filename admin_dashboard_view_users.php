
<?php





require_once 'classes/DbConnector.php'; // Adjust the path as per your directory structure
require_once 'classes/Pet.php'; // Adjust the path as per your directory structure

use classes\Pet;

// Fetch pets data
$pets = Pet::getAllPets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
     <head>
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





</head>
<style>
body {
    overflow: hidden; /* Disable body scrolling */
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

 .navbar-brand img {
      margin-right: 20px;
    }
#page-content-wrapper {
    height: 100vh;
    overflow-y: auto;
    width: 100%;
    padding: 20px;
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



    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #4C056C;
        color: #fff;
    }

    .table tbody+tbody {
        border-top: 2px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
</style>
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

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
           
        </nav>

      <div style="margin-top: 40px; margin-left: 20px;">
        <h2 style="color: #4C056C;">User Details</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                      
                        <th>Address</th>
                        <th>birthday</th>
                        <th>Age</th>
                        <th>NIC</th>
                        <th>Email</th>
                        <th>Contact</th>
                        
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once 'classes/DbConnector.php';
                        require_once 'classes/User.php';

                        use classes\DbConnector;
                        use classes\User;

                        $con = DbConnector::getConnection();
                        $users = User::getAllUsers($con);
foreach ($users as $user) {
                            $fullName = $user['first_name'] . ' ' . $user['last_name'];
                            echo "<tr>
                                <td>{$user['id']}</td>
                                <td>{$fullName}</td>
                                <td>{$user['address']}</td>
                                <td>{$user['dob']}</td>
                                <td>{$user['age']}</td>
                                <td>{$user['nic']}</td>
                                <td>{$user['email']}</td>
                                <td>{$user['contact_number']}</td>
                                
                                <td>{$user['gender']}</td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<script>
function confirmDelete(petId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_pet.php?id=' + petId;
        }
    });
}
</script>


</body>



</html>
