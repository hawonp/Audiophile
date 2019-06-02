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

  <title>Auction</title>
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
    <a class = "active" href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- <hr> -->

  <h2> Items currently on auction! </h2>
  <!-- Table of items currently on auction -->
  <div class="selling">
    <?php
      $sql = "SELECT * FROM Auction a, Item i Where a.iid = i.iid";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo "<table><tr><th>Item name</th> <th>Current bidder</th> <th>Current bid price</th> <th>Auction end date</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["iname"]."</td> <td>".$row["email"]."</td> <td>".$row["curr_bid"]."</td> <td>".$row["end_date"]." won</td></tr>";
        }
        echo "</table>";
      } else {
      echo "0 results";
      }
    ?>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
