<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }
  //Accessing db
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/styles.css" rel="stylesheet">
  <?php
    $sql = "SELECT i.iname FROM Item i WHERE i.iid=$iid";
    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      echo "<title>".$row["iname"]."</title>";
    } else {
      echo "SUCH ITEM DOESN'T EXIST";
    }
  ?>
</head>

<body>
  <!-- Navigation -->
  <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- Item description -->
  <div class="row">
    <!-- Item image -->
    <div class="itemDes">
      <?php
        echo "<img src='images/".$row["iname"].".jpg' alt='Item image'>";
      ?>
    </div>
    <!-- Item Description -->
    <div class="itemDes">
      <h2 color="#4CAF50"> <?php echo $row["iname"]; ?></h2>  
      <hr>
      dd
    </div>
  </div>

  <!-- hr -->
  <hr>

  <!-- Discussion -->
  <div class="posts">
    
  </div>

  <!-- hr -->
  <hr>

  <!-- Reviews -->
  <div class="posts">
    
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>

</html>
