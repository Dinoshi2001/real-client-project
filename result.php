<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Selector Quiz Results</title>
  <style>

    body {
      font-family: 'Comfortaa', cursive;
      margin: 0;
      color:#420c52;
      background: linear-gradient(to right, #f8f8ff, #e0e0f7); /* Soft gradient background */
      text-align: center;
      overflow-x: hidden; /* Prevent horizontal scroll */
    }

    .header-bar {
      width: 100%;
      background-color:#420c52; /* Solid purple background */
      color: white;
      padding: 20px 0;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      margin-bottom: 30px;
    }

    .header-bar h1 {
      font-size: 2.5em;
      margin: 0;
      letter-spacing: 8px;
      text-transform: uppercase;
    }

    .header-bar p {
      font-size: 1.4em;
      margin: 10px 0 0;
    }

    .content {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9); /* Transparent background */
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      max-width: 500px;
      width: 90%;
      margin-left: auto;
      margin-right: auto;
    }

    .pet-image-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 0;
    }

    .pet-name {
      font-size: 2em;
      font-weight: bold;
      color: #8615a2;
      margin-bottom: 15px;
    }

    .pet-image {
  width: 350px;
  height: 250px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  border-radius: 20px; /* Slight rounding for the corners of the rectangle */
}

.pet-image img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensures the image covers the area without distorting */
  transition: transform 0.3s ease-in-out;
}

.pet-image img:hover {
  transform: scale(1.05); /* Subtle zoom on hover */
}

    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 15px 30px;
      font-size: 18px;
      color: white;
      background-color:#8615a2;
      border: none;
      border-radius: 50px;
      text-decoration: none;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #C3B1E1; /* Lighter purple-pink hover effect */
    }

    /* Letter-by-letter animation */
    .letter-animation {
      display: inline-block;
      opacity: 0;
      animation: fadeIn 1s forwards;
    }

    .header-bar h1 .letter-animation:nth-child(1) { animation-delay: 0.1s; }
    .header-bar h1 .letter-animation:nth-child(2) { animation-delay: 0.2s; }
    .header-bar h1 .letter-animation:nth-child(3) { animation-delay: 0.3s; }
    .header-bar h1 .letter-animation:nth-child(4) { animation-delay: 0.4s; }
    .header-bar h1 .letter-animation:nth-child(5) { animation-delay: 0.5s; }
    .header-bar h1 .letter-animation:nth-child(6) { animation-delay: 0.6s; }
    .header-bar h1 .letter-animation:nth-child(7) { animation-delay: 0.7s; }
    .header-bar h1 .letter-animation:nth-child(8) { animation-delay: 0.8s; }
    .header-bar h1 .letter-animation:nth-child(9) { animation-delay: 0.9s; }
    .header-bar h1 .letter-animation:nth-child(10) { animation-delay: 1s; }
    .header-bar h1 .letter-animation:nth-child(11) { animation-delay: 1.1s; }
    .header-bar h1 .letter-animation:nth-child(12) { animation-delay: 1.2s; }
    .header-bar h1 .letter-animation:nth-child(13) { animation-delay: 1.3s; }
    .header-bar h1 .letter-animation:nth-child(14) { animation-delay: 1.4s; }
    .header-bar h1 .letter-animation:nth-child(15) { animation-delay: 1.5s; }
    .header-bar h1 .letter-animation:nth-child(16) { animation-delay: 1.6s; }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(-50px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    /* GIF styling */
    .congrats-gif {
      
      width: 100px;
      height: auto;
    }

    /* Responsive design for smaller devices */
    @media (max-width: 768px) {
      .header-bar h1 {
        font-size: 2.2em;
      }

      .content {
        width: 100%;
        padding: 10px;
      }

      .pet-image {
        width: 250px;
        height: 250px;
      }

      .btn {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>
  <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project02";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"):

      $pet_type = htmlspecialchars($_POST['pet_type']);
      $pet_cost = htmlspecialchars($_POST['pet_cost']);
      $income_range = htmlspecialchars($_POST['income_range']);
      $time_dedication = htmlspecialchars($_POST['time_dedication']);
      $children = htmlspecialchars($_POST['children']);
      $living_situation = htmlspecialchars($_POST['living_situation']);

      // Retrieve the last quiz result
      $sql = "SELECT * FROM quiz_results WHERE pet_type='$pet_type' AND pet_cost='$pet_cost' AND time_dedication='$time_dedication' AND income_range='$income_range' AND children='$children' AND living_situation='$living_situation'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          // Initialize suitable pet variables
          $suitable_pet = $row['breeds'];
          $pet_image = $row['img'];
    ?>

    <!-- Header bar with congratulations message -->
    <div class="header-bar">
      <h1>
        <?php
          // Split the word "Congratulations" into individual letters for animation
          $word = str_split('Congratulations!');
          foreach ($word as $letter) {
              echo "<span class='letter-animation'>$letter</span>";
          }
        ?>
      </h1>
      <img src="images/congrat1.gif" class="congrats-gif" alt="Congratulations">
      <p>Your Perfect Pet is:</p>
    </div>

    <!-- Pet name and image centered on the page -->
    <div class="content">
      <div class="pet-image-container">
        <div class="pet-name"><?php echo $suitable_pet; ?></div>
        <div class="pet-image">
          <img src="<?php echo $pet_image; ?>" alt="<?php echo $suitable_pet; ?>">
        </div>
      </div>
      <a href="index.php" class="btn">Do the Quiz Again</a>
    </div>

    <?php 
  } else { 
      echo "No matching pets"; 
  ?>
      <a href="index.php" class="btn">Do the Quiz Again</a>
  <?php
  } 
endif;
?>
<br><br><br>
</body>
</html>

