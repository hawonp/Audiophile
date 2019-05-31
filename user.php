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
    <a class="active" href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="#login">LOGIN</a>
  </div>


  <!-- User info -->
  <div>
    <img src="images/User_image.png" alt="User image">
  </div>
  <!-- <hr> -->
  <hr>
  <!-- #TODO
  FOR  Joseph and Sue
  Make user's personal page
  it should contain the user's selling item
  the auction that the user attended(?)
  the items that user bought
  User information-->

  <!-- Purchase history-->
  <div class = "selling">
    <h3> Your purchase history</h3>
    <!-- retreiving data drom db -->
    <?php
      $usr_email="lol@gmail.com"; /* TODO:Change to get login info later */
      $sql = "SELECT i.iname, bs.bdate FROM Item i JOIN (SELECT b.iid, b.bdate FROM Buys b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
      $result = $conn->query($sql);
    
      if ($result->num_rows > 0) {
        echo "<table width=\"80%\"><tr><th align=\"left\">Item name</th><th align=\"right\">Purchase date</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["iname"]."</td><td align=\"right\">".$row["bdate"]."</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<i>You have not bought anything yet!</i>";
      }
    ?>
  </div>
  <!-- Auction history-->
  <div>
    <?php
      $conn->close();
    ?>
  </div>

  
  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
