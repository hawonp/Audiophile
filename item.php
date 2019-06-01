<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }
  $iid = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/styles.css" rel="stylesheet">

  <title>USERNAMEPLACEHOLDER</title>
</head>

<body>
  <!-- Accessing db -->
  <?php
    $servername = "localhost";
    $username = "user";
    $password = "hey";
    $dbname = "auction_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
  ?>
  <!-- Navigation -->
  <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlignActive" href="user.php">YOUR PROFILE</a>
  </div>

  

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>

</html>
