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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<style>
body {
    overflow: hidden;
    height: 100vh;
    margin: 0;
    display: flex;
    font-family: 'Comfortaa', cursive;
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
    background-color: #4C056C;
    color: #ffffff;
}

#page-content-wrapper {
    height: 100vh;
    overflow-y: auto;
    width: 100%;
    padding: 20px;
}

.profile-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
    vertical-align: middle;
}

.sidebar-heading a {
    text-decoration: none;
    display: inline-block;
}

.sidebar-heading {
    padding: 10px 15px;
    font-size: 1.25rem;
    color: #ffffff;
}

.list-group-item {
    border: none;
    padding: 15px 20px;
    background-color: #4C056C;
    color: #ffffff;
}

.list-group-item:hover {
    color: white;
    background-color: #670D8F;
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
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Dashboard</a>
            </div>
        </nav>

        <!-- Table start -->
        <div style="margin-top: 40px; margin-left: 20px;">
            <h2 style="color: #4C056C;">Pet Details</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pet Type</th>
                            <th>Brand</th>
                            <th>Country</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pets as $pet): ?>
                        <tr>
                            <td><?php echo $pet->id; ?></td>
                            <td><?php echo $pet->pet_type; ?></td>
                            <td><?php echo $pet->brand; ?></td>
                            <td><?php echo $pet->country; ?></td>
                            <td><?php echo $pet->age; ?></td>
                            <td><?php echo $pet->gender; ?></td>
                            <td><?php echo $pet->price; ?></td>
                            <td><?php echo $pet->description; ?></td>
                            <td>
                                <?php $imagePath = 'uploads/' . $pet->image; ?>
                                <img src="<?php echo $imagePath; ?>" alt="Pet Image" style="width: 100px; height: auto;">
                            </td>
                            <td>
                                <a href="#" title="Edit" data-toggle="modal" data-target="#editModal<?php echo $pet->id; ?>">
                                    <i class="fas fa-edit" style="color: green; margin-left: 0px; margin-right: 10px;"></i>
                                </a>
                                <a href="javascript:void(0);" title="Delete" onclick="confirmDelete(<?php echo $pet->id; ?>)">
                                    <i class="fas fa-trash-alt" style="color: #dc3545;"></i>
                                </a>
                            </td>
                        </tr>
<!-- Bootstrap Modal for Editing Pet -->
<div class="modal fade" id="editModal<?php echo $pet->id; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $pet->id; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?php echo $pet->id; ?>">Edit Pet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm<?php echo $pet->id; ?>" action="update_pet.php" method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission(this, event)">
                    <input type="hidden" name="petId" value="<?php echo $pet->id; ?>">
                    <div class="mb-3">
                        <label for="petType<?php echo $pet->id; ?>" class="form-label">Pet Type</label>
                        <input type="text" class="form-control" name="petType" id="petType<?php echo $pet->id; ?>" value="<?php echo $pet->pet_type; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="brand<?php echo $pet->id; ?>" class="form-label">Brand</label>
                        <input type="text" class="form-control" name="brand" id="brand<?php echo $pet->id; ?>" value="<?php echo $pet->brand; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="country<?php echo $pet->id; ?>" class="form-label">Country</label>
                        <input type="text" class="form-control" name="country" id="country<?php echo $pet->id; ?>" value="<?php echo $pet->country; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="age<?php echo $pet->id; ?>" class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" id="age<?php echo $pet->id; ?>" value="<?php echo $pet->age; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender<?php echo $pet->id; ?>" class="form-label">Gender</label>
                        <select class="form-control" name="gender" id="gender<?php echo $pet->id; ?>" required>
                            <option value="Male" <?php if ($pet->gender == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($pet->gender == 'Female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price<?php echo $pet->id; ?>" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" id="price<?php echo $pet->id; ?>" value="<?php echo $pet->price; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description<?php echo $pet->id; ?>" class="form-label">Description</label>
                        <textarea class="form-control" id="description<?php echo $pet->id; ?>" name="description" rows="3" required><?php echo $pet->description; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="existingImage<?php echo $pet->id; ?>" class="form-label">Current Image</label>
                        <img src="<?php echo $imagePath; ?>" alt="Pet Image" id="existingImage<?php echo $pet->id; ?>" style="width: 100px; height: auto;">
                    </div>
                    <div class="mb-3">
                        <label for="image<?php echo $pet->id; ?>" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" name="image" id="image<?php echo $pet->id; ?>" onchange="previewImage(event, 'existingImage<?php echo $pet->id; ?>')">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Pet details updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmSubmission(form, event) {
        event.preventDefault();
        if (confirm('Are you sure you want to save these changes?')) {
            // If the user confirms, submit the form
            form.submit();
            // Show success message after form submission
            $('#editModal<?php echo $pet->id; ?>').on('hidden.bs.modal', function () {
                $('#successModal').modal('show');
            });
        } else {
            // If the user cancels, do nothing
            return false;
        }
    }

    function previewImage(event, imageId) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById(imageId);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Table end -->
    </div>
</div>

<script>
function confirmDelete(id) {
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
            window.location.href = 'delete_pet.php?id=' + id;
        }
    })
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    
    if (message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    }
});
</script>

</body>
</html>
