<?php
session_start(); // Start the session

require 'dbConnector.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_form.php");
    exit;
}

$user = $_SESSION['user']; // User details from session

if (!isset($user['id'])) {
    die("User ID is not set in session. Please log in again.");
}

// Include your DbConnector class file
require_once 'classes/DbConnector.php'; // Adjust the path as necessary

use classes\DbConnector;

// Get the database connection
$conn = DbConnector::getConnection();

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
  $petId = intval($_GET['id']);

  // Prepare a statement to prevent SQL injection
  $stmt = $conn->prepare('SELECT * FROM pets WHERE id = ?');
  $stmt->execute([$petId]);

  // Check if a pet is found
  if ($stmt->rowCount() > 0) {
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
  } else {
    echo "Pet not found.";
    exit;
  }
} else {
  echo "Invalid request.";
  exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pet['pet_type']); ?> Profile</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<style>
  body {
    background: linear-gradient(135deg, #f8f9fa 25%, #e0e0e0 100%);
    font-family:'Comfortaa', cursive;
    position: relative;
  }

  h1 {
    font-size: 3rem;
    color: black;
    text-shadow: 2px 2px #e0e0e0;
    padding-top: 50px;
  }

  .container {
    text-align: center;
  }

  .pet-card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s;
    max-width: 1100px;
    margin: auto;
  }

  /* .pet-card:hover {
    
    transform: scale(1.02);
  } */

  .pet-img {
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
    height: 100%;
    /* Let the height adjust automatically */
    width: 100%;
    /* Ensure the width fills the entire card */
    transition: transform 0.3s ease-in-out;
  }



  .pet-img:hover {
    transform: scale(1.1);
  }

  .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .card-title {
    font-size: 1.75rem;
    margin-bottom: 1rem;
    color: #343a40;
  }

  .card-text {
    margin-bottom: 0.75rem;
    color: #6c757d;
    text-align: left;
    width: 100%;
  }

  .btn-primary {
    background-color: #007bff;
    border: none;
    padding: 0.75rem 1.25rem;
    border-radius: 25px;
    font-size: 1rem;
    transition: background-color 0.3s ease-in-out;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  .btn-secondary {
    background-color: #0B040E;
    border: none;
    padding: 0.75rem 1.25rem;
    border-radius: 25px;
    font-size: 1rem;
    transition: background-color 0.3s ease-in-out;
  }

  .btn-secondary:hover {
    background-color: #575259;
    color: white;
  }

  .card-body button+button {
    margin-top: 10px;
  }

  .place-order-btn {
    background-color: #550A78;
  }

  .place-order-btn:hover {
    background-color: #780DA9;
    /* Change to the desired hover color */
  }


  .label-margin {
    margin-right: 0px;
    /* Adjust the value as needed */
  }

  .modal-header {
    background-color: #6F099E;
    color: white;
  }

  .modal-content {
    border-radius: 20px;
  }

  .modal-footer {
    display: flex;
    justify-content: space-between;
  }

  .payment-icons img {
    width: 50px;
    margin: 5px;
  }

  .vertical-line {
    border-left: 2px solid #ddd;
    height: 100%;
    position: absolute;
    left: 50%;
    top: 0;
  }

  .loadingSvg {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 20;
  }

  .popup-container {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
  }

  .spinner {
    position: absolute;
    display: none;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }

  .popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 400px;
    position: relative;
    text-align: center;
  }

  .close-btn {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 30px;
    cursor: pointer;
  }

  .proceed-btn,
  .cancel-btn {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
    margin: 10px;
    transition: 0.3s;
  }

  .proceed-btn {
    background-color: #550A78;
    color: #FFFFFF;
  }

  .proceed-btn:hover {
    filter: brightness(0.9);
  }

  .cancel-btn {
    background-color: grey;
    color: #FFFFFF;
  }

  .cancel-btn:hover {
    filter: brightness(0.9);
  }
</style>

<body>
  <!-- <img src="assets/spinner.svg" alt="" class="loadingSvg"> -->
  <h1 style="font-size:50px; color:#6F099E; text-align: center; padding-top: 50px;">Pet Profile</h1>
  <div class="container mt-5">

    <div class="card pet-card">
      <div class="row no-gutters">
        <div class="col-md-6">
          <img src="uploads/<?php echo htmlspecialchars($pet['image']); ?>" class="card-img pet-img" alt="Image of <?php echo htmlspecialchars($pet['pet_type']); ?>">
        </div>
        <div class="col-md-6">
          <div style="text-align: center; margin-left:50px;" class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($pet['pet_type']); ?></h5>
            <p class="card-text"><strong class="label-margin">brand:</strong> <?php echo htmlspecialchars($pet['brand']); ?></p>
            <p class="card-text"><strong class="label-margin">Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
            <p class="card-text"><strong class="label-margin">Gender:</strong> <?php echo htmlspecialchars($pet['gender']); ?></p>
            <p class="card-text"><strong class="label-margin">Price:</strong> $<?php echo htmlspecialchars($pet['price']); ?></p>
            <p class="card-text"><strong class="label-margin">Description:</strong> <?php echo htmlspecialchars($pet['description']); ?></p>
          </div>

          <div class="container mt-5">
            <a href="#" class="btn btn-secondary mb-4 place-order-btn" data-toggle="modal" data-target="#paymentModal">Place Order</a><br>
            <a style="background-color: black;" href="userside_pets.php" class="btn btn-secondary mb-4 place-order-btn">back</a>



          </div>




          <?php
          // Check if user is logged in
          if (isset($_SESSION['user'])) {
            // Get user data from session
            $user = $_SESSION['user'];
          ?>




          <?php } else {
            // If user is not logged in, display a message or redirect to login page
            echo "<p>Please log in to see your profile.</p>";
          }
          ?>


          <!-- Payment Modal -->
          <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="paymentModalLabel">Payment Gateway</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="paymentForm" method="POST" action="process_payment.php">
                    <div class="row">
                      <!-- Customer Details -->
                      <div class="col-md-5">
                        <h6>Customer Details</h6>
                        <div class="form-group">
                          <label for="customerName">Name</label>
                          <input type="text" value="<?php echo $user['first_name'] . $user['last_name']; ?>" class="form-control" id="customerName" required>
                        </div>
                        <div class="form-group">
                          <label for="customerAddress">Address</label>
                          <input type="text" value="<?php echo $user['address'] ?>" class="form-control" id="customerAddress" required>
                        </div>
                        <div class="form-group">
                          <label for="customerEmail">Email ID</label>
                          <!-- <input type="email" class="form-control" value="<?php echo $user['email'] ?>" id="customerEmail" required> -->
                          <input type="email" class="form-control" name="customerEmail" value="<?php echo $user['email']; ?>" id="customerEmail" required>
                        </div>
                        <div class="form-group">
                          <label for="customerContact">Contact Number</label>
                          <input type="tel" class="form-control" value="<?php echo $user['contact_number'] ?>" id="customerContact" required>
                        </div>
                        <div class="form-group">
                          <label for="customerNIC">NIC Number</label>
                          <input type="text" class="form-control" value="<?php echo $user['nic'] ?>" id="customerNIC" required>
                        </div>
                      </div>

                      <!-- Vertical Line -->
                      <div class="col-md-1 position-relative">
                        <div class="vertical-line"></div>
                      </div>

                      <!-- Pet Details -->
                      <div class="col-md-6">
                        <h6>Pet Details</h6>

                        <div class="form-group">
                          <label for="petName">Pet ID</label>
                          <!-- <input type="text" class="form-control" value=" <?php echo htmlspecialchars($pet['id']); ?>" id="petName" required> -->
                          <input type="text" class="form-control" name="petID" value="<?php echo $pet['id']; ?>" id="petID" required>
                        </div>
                        <div class="form-group">
                          <label for="petName">Pet Name</label>
                          <input type="text" class="form-control" name="petName" value=" <?php echo htmlspecialchars($pet['pet_type']); ?>" id="petName" required>
                        </div>
                        <div class="form-group">
                          <label for="petBrand">Brand</label>
                          <input type="text" class="form-control" name="petBrand" value=" <?php echo htmlspecialchars($pet['brand']); ?>" id="petBrand" required>
                        </div>
                        <div class="form-group">
                          <label for="petGender">Gender</label>
                          <input type="text" class="form-control" name="petGender" value=" <?php echo htmlspecialchars($pet['gender']); ?>" id="petGender" required>
                        </div>
                        <div class="form-group">
                          <label for="petPrice">Price</label>
                          <input type="text" class="form-control" name="petPrice" value=" <?php echo htmlspecialchars($pet['price']); ?>" id="petPrice" required>
                        </div>
                      </div>
                    </div>

                    <!-- Payment Details -->
                    <hr style="height: 20px;">
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <h6>Payment Details</h6>
                        <div class="form-group">
                          <label for="cardNumber">Credit Card Number</label>
                          <!-- <input type="text" class="form-control" id="cardNumber" required> -->
                          <input type="text" class="form-control" id="card-number" name="card-number" maxlength="19" placeholder="#### #### #### ####">
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="expMonth">Exp Month</label>
                            <!-- <input type="text" class="form-control" id="expMonth" required> -->
                            <input type="text" class="form-control" id="expiration-month" name="expiration-month" maxlength="2" placeholder="MM">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="expYear">Exp Year</label>
                            <!-- <input type="text" class="form-control" id="expYear" required> -->
                            <input type="text" class="form-control" id="expiration-year" name="expiration-year" maxlength="4" placeholder="YYYY">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="cvv">CVV</label>
                            <!-- <input type="text" class="form-control" id="cvv" required> -->
                            <input type="text" class="form-control" id="cvn" name="cvn" maxlength="3" placeholder="###">
                          </div>
                        </div>
                        <div class="form-group payment-icons">
                          <label>Accepted Cards</label>
                          <div>
                            <img src="images/card4.jpg" alt="Visa">
                            <img src="images/card2.jpg" alt="MasterCard">
                            <img src="images/card3.webp" alt="Amex">
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>



                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button style="background-color:#7310A2;" class="btn btn-primary" id="pay-checkout" onclick="closePopup('confirmationPopup')">Checkout</button>
                  </div>
                  <div id="confirmationPopup" class="popup-container">
                    <div class="popup-content">
                      <span class="close-btn" onclick="closePopup('confirmationPopup')">&times;</span>
                      <h2>Confirm Change</h2>
                      <p>Are you sure you want to proceed your payment?</p>
                      <button id="proceedBtn" class="proceed-btn">Proceed</button>
                      <button class="cancel-btn" onclick="closePopup('confirmationPopup')">Cancel</button>
                    </div>
                    <img src="assets/spinner.svg" alt="Spinner" id="loadspinner" class="spinner">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
          <script>
            function showPopup(popupId) {
              document.getElementById(popupId).style.display = 'flex';
            }

            function closePopup(popupId) {
              document.getElementById(popupId).style.display = 'none';
            }
          </script>
          <script src="payment.js"></script>





</body>

</html>