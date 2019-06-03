<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }
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

$iid = $_GET['iid'];
$seller = $_GET['seller'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/login.css" rel="stylesheet">
  <!-- <link href="./css/button.css" rel="stylesheet">
  <link href="./css/styles.css" rel="stylesheet"> -->

  <title>Purchase product</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">
</head>

<body>

  

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>

</html>
