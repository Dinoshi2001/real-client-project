<?php
// Include required classes
include 'classes/DbConnector.php';
include 'classes/Pet.php';
include 'classes/User.php';

use classes\DbConnector;
use classes\Pet;
use classes\User;

// Get the database connection
$conn = DbConnector::getConnection();

// Initialize $waitingList to an empty array
$waitingList = [];

// Fetch waiting list data
try {
    $query = "SELECT w.id, u.email AS user_email, w.pet_type, w.pet_brand, w.created_at
              FROM waiting_list w
              JOIN users u ON w.user_id = u.id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $waitingList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle query error
    error_log("Database query error: " . $e->getMessage());
}

// Fetch data for charts
try {
    $chartQuery = "SELECT pet_type, COUNT(*) AS count FROM waiting_list GROUP BY pet_type";
    $chartStmt = $conn->prepare($chartQuery);
    $chartStmt->execute();
    $petTypeData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);

    $brandChartQuery = "SELECT pet_brand, COUNT(*) AS count FROM waiting_list GROUP BY pet_brand";
    $brandChartStmt = $conn->prepare($brandChartQuery);
    $brandChartStmt->execute();
    $petBrandData = $brandChartStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch pet brand data for each pet type
    $brandsByType = [];
    $types = ['Dog', 'Cat', 'Bird', 'Rabbit'];
    foreach ($types as $type) {
        $typeQuery = "SELECT pet_brand, COUNT(*) AS count FROM waiting_list WHERE pet_type = :pet_type GROUP BY pet_brand";
        $typeStmt = $conn->prepare($typeQuery);
        $typeStmt->execute(['pet_type' => $type]);
        $brandsByType[$type] = $typeStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Handle query error
    error_log("Database query error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    /* styles.css */

    html, body {
    height: 100%; /* Full height for both html and body */
    margin: 0;
    overflow: hidden; /* Prevent scrolling for the entire page */
}

#wrapper {
    display: flex;
    width: 100%;
    height: 100vh; /* Full height for the wrapper */
}

#sidebar-wrapper {
    height: 100vh; /* Full height for sidebar */
    overflow-y: auto; /* Allow scrolling inside the sidebar */
    width: 250px;
    background-color: #4C056C; 
    color: #ffffff;
}

#page-content-wrapper {
    width: 100%;
    height: 100vh; /* Full height for content area */
    overflow-y: auto; /* Allow scrolling inside the content area */
    padding: 20px;
    box-sizing: border-box;
}

.container {
    padding: 20px;
    height: 100%;
    overflow-y: auto; /* Enable scrolling within the content container */
}



body {
         font-family:'Comfortaa', cursive;
  
    overflow-x: hidden;
    height: 100vh;
    margin: 0;
    display: flex;
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



 * {
            font-family: 'Comfortaa', cursive;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            outline: none;
            border: none;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;

            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4b0082;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .message {
            margin-bottom: 20px;
            color: #4b0082;
        }
        .card {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .chart-title {
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
            text-align: center; /* Center title for all charts */
        }
        .charts-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .chart {
            width: 20%; /* Adjusted width to fit two charts in one line */
            margin: 1%;
            text-align: center; /* Center chart title and canvas */
        }
        .chart canvas {
            width: 100% !important; /* Ensure pie charts fit the container */
            height: auto !important; /* Maintain aspect ratio */
        }
        .bar-chart-container {
            text-align: center;
        }
        .bar-chart-container canvas {
            width: 100% !important; /* Adjusted width for the bar chart */
            height: 400px !important; /* Fixed height for the bar chart */
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

     <div class="container">
        <h1>Waiting List</h1>
        <div class="message">Here is the list of users waiting for pets.</div>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Email</th>
                        <th>Pet Type</th>
                        <th>Pet Brand</th>
                        <th>Added At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($waitingList)): ?>
                        <?php foreach ($waitingList as $entry): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($entry['id']); ?></td>
                                <td><?php echo htmlspecialchars($entry['user_email']); ?></td>
                                <td><?php echo htmlspecialchars($entry['pet_type']); ?></td>
                                <td><?php echo htmlspecialchars($entry['pet_brand']); ?></td>
                                <td><?php echo htmlspecialchars($entry['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No entries found in the waiting list.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card bar-chart-container">
            <div class="chart-title">Pet Type Requests</div>
            <canvas id="petTypeChart"></canvas>
        </div>
        <div class="card">
            <div class="chart-title">Pet Type Distribution</div>
            <div class="charts-container">
                <?php foreach ($brandsByType as $type => $brands): ?>
                    <div class="chart">
                        <div class="chart-title"><?php echo $type; ?> Brands</div>
                        <canvas id="chart-<?php echo strtolower($type); ?>"></canvas>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        // Data for Pet Type Chart
        const petTypeData = <?php echo json_encode($petTypeData); ?>;
        const petTypeLabels = petTypeData.map(data => data.pet_type);
        const petTypeCounts = petTypeData.map(data => data.count);

        // Create Pet Type Chart
        const ctxPetType = document.getElementById('petTypeChart').getContext('2d');
        const petTypeChart = new Chart(ctxPetType, {
            type: 'bar',
            data: {
                labels: petTypeLabels,
                datasets: [{
                    label: 'Number of Requests',
                    data: petTypeCounts,
                    backgroundColor: 'rgba(75, 0, 130, 0.2)',
                    borderColor: 'rgba(75, 0, 130, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Create Pie Charts for Pet Types
        <?php foreach ($brandsByType as $type => $brands): ?>
            const ctx<?php echo strtolower($type); ?> = document.getElementById('chart-<?php echo strtolower($type); ?>').getContext('2d');
            const <?php echo strtolower($type); ?>Chart = new Chart(ctx<?php echo strtolower($type); ?>, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($brands, 'pet_brand')); ?>,
                    datasets: [{
                        label: '<?php echo $type; ?> Brands',
                        data: <?php echo json_encode(array_column($brands, 'count')); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        <?php endforeach; ?>
    </script>

        
    </div>
</div>
        <!-- /#sidebar-wrapper -->

        
</body>
</html>
