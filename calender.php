<?php

include_once "classes/Events.php";

// Create object from Events class
$eventsObj = new Events();

$events = $eventsObj->displayData();
$event_table = $eventsObj->displayDataForTable();

if (isset($_POST['submit'])) {
    $eventsObj->storeData($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <script src="https://kit.fontawesome.com/f2ab1a3f38.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
            margin: 0;
            padding: 0;
        }

        #sidebar-wrapper {
            height: 100vh;
            overflow-y: auto;
            width: 250px;
            flex-shrink: 0;
            background-color: #4C056C;
            color: #ffffff;
            margin: 0;
            padding: 0;
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

        #page-content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0;
            margin: 0;
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

        .table {
            font-size: 14px;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        .table th {
            background-color: #0A0B4F;
            color: white;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        #calendar {
            max-width: 600px;
            height: 650px;
            margin: 0 auto;
            background-color: #FCEDFF;
            color: white;
            padding: 70px;
            margin-top: 50px;
            margin-bottom: 50px;
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

            <!-- Content Area -->
            <main class="col-md-12 col-12 ms-sm-auto">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div id='calendar'></div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="Calender.php" method="POST">
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description:</label>
                                                <input type="text" class="form-control" name="description" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Start Date:</label>
                                                <input type="date" class="form-control" name="start_date" required>
                                            </div>
                                            <div class="form-group">
                                                <label>End Date:</label>
                                                <input type="date" class="form-control" name="end_date" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" name="submit" class="btn btn-primary" style="float:right; background-color: darkblue; margin-top: 10px;" value="Create">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <th>Event</th>
                                                <th>Description</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody>
                                                <?php
                                                if (is_array($event_table)) {
                                                    foreach ($event_table as $data) {
                                                        $startDate = date('Y-m-d', strtotime($data['start']));
                                                        $endDate = date('Y-m-d', strtotime($data['end']));

                                                        echo "<tr>";
                                                        echo "<td>" . $data['title'] . "</td>";
                                                        echo "<td>" . $data['description'] . "</td>";
                                                        echo "<td>" . $startDate . "</td>";
                                                        echo "<td>" . $endDate . "</td>";
                                                        echo "<td><a href='delete_event.php?event_id=" . $data['id'] . "' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $data['id'] . "); return false;'>Delete</a></td>";
                                                        echo "</tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo $events; ?>,
                allDay: false,

                displayEventTime: false
            });
            calendar.render();
        });

        function confirmDelete(eventId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_event.php?event_id=' + eventId;
                }
            });
        }

        function showSuccessMessage() {
            Swal.fire({
                title: 'Deleted!',
                text: 'Event has been deleted.',
                icon: 'success',
                timer: 1500
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        const deleteSuccess = urlParams.get('deleteSuccess');
        if (deleteSuccess === '1') {
            showSuccessMessage();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>
