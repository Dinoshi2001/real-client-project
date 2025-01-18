<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
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
</style>
<body>

<?php
// Check for status parameter in the URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    switch ($status) {
        case 0:
            // No form submission
            echo '<script>
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "No form submission detected.",
                });
            </script>';
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
            // File upload error.
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "File upload error.",
                });
            </script>';
            break;
        case 3:
            // Pet added successfully!
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Pet added successfully!",
                });
            </script>';
            break;
        case 4:
            // Unable to add pet.
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Unable to add pet.",
                });
            </script>';
            break;
        default:
            // Unknown error.
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "An unknown error occurred.",
                });
            </script>';
            break;
    }
}
?>

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
        <!--
        <nav style="background-color:#4C056C; class="navbar navbar-expand-lg navbar border-bottom">
            <div class="container-fluid">
                <a style="background-color:#4C056C; color: white;" class="navbar-brand" href="#">Admin Dashboard</a>
            </div>
        </nav>-->
        <div style="width:800px; border: #c1c1c1 solid 1px; padding:30px; margin-top:40px; border-color: purple;" class="container-fluid">
            <div class="container">
                <div class="form-container">
                    <h2 style="padding:10px; color: #4C056C;">Add a New Pet</h2>
                    <form id="addPetForm" action="process_pet.php" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="petType">Pet Type</label>
                                <input type="text" class="form-control" id="petType" name="petType" placeholder="Enter pet type" required>
                            </div>
                            <div class="col-md-6">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter brand" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" required>
                            </div>
                            <div class="col-md-6">
                                <label for="age">Age</label>
                                <input type="number" class="form-control" id="age" name="age" placeholder="Enter age" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" required>
                        </div>
                        <button style="background-color:#4C056C;" type="submit" class="btn btn-primary btn-block">Add Pet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Intercept form submission
    document.getElementById('addPetForm').addEventListener('submit', function (event) {
        // Prevent default form submission
        event.preventDefault();
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to add this pet?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            // If user confirms, submit the form
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    });
});
</script>
</body>
</html>