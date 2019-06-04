<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/styles.css" rel="stylesheet">

  <title>Audiophile</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <!-- <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a class = "active" href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div> -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">Audiophile</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="selling.php">Sales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="auction.php">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user.php">My Page</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- <hr> -->

  <p> hello it sales </p>

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

$sql = "SELECT * FROM Sells S, Item I Where S.iid = I.iid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Item name</th><th>Sell Price</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</td><td>".$row["sellprice"]." won</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

?>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
