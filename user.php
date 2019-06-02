<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  } else {
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

    $myemail = $_SESSION['email'];

    $sql = "SELECT * FROM User WHERE email = '$myemail'";
    $result = mysqli_query($conn,$sql);
    // $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    // $count = mysqli_num_rows($result);

    $row = $result->fetch_assoc();

    // echo $row['first_name'];
    $myfname = $row['first_name'];
    $mylname = $row['last_name'];
    $myemail = $row['email'];
    $mypnum = $row['phone_num'];
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
  <link href="./css/user.css" rel="stylesheet">
  <link href="./css/button.css" rel="stylesheet">

  <title>Your profile</title>
</head>

<body>
  <!-- Navigation -->
  <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlignActive" href="user.php">YOUR PROFILE</a>
  </div>

  <center>
  <!-- User info -->
  <div class = "profile" >
    <img src="images/User_image.png" alt="User image">
    <h1> <?php echo $myfname. " ". $mylname; ?> </h1>
    <p class = "email"> <?php echo $myemail; ?> </p>

    <p class = "number"> <?php echo $mypnum; ?> </p>

  </div>
  <!-- <hr> -->
  <!-- <hr> -->
  <!-- #TODO
  FOR  Joseph and Sue
  Make user's personal page
  it should contain the user's selling item
  the auction that the user attended(?)
  the items that user bought
  User information-->

  <!-- Your items -->
  <div class = "selling">
    <h2> Your Items!</h2>
    <!-- retreiving data from db -->
    <?php
      $usr_email=$_SESSION['email'];
      $sql = "SELECT i.iname, bs.stock FROM Item i JOIN (SELECT b.iid, b.stock FROM Sells b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo "<table width=\"50%\"><tr><th align=\"left\">Item name</th><th align=\"right\">Stock</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["iname"]."</td><td align=\"right\">".$row["stock"]."</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<i>You are not selling anything right now!</i>";
      }
    ?>
    <br>
    <button class = "button_sell" onclick="window.location.href='/auction/sellItem.php'">SELL MORE ITEMS! </button>
  </div>

  <!-- Purchase history-->
  <div class = "selling">
    <h2> Your purchase history</h2>
    <!-- retreiving data from db -->
    <?php
      $usr_email=$_SESSION['email'];
      $sql = "SELECT i.iname, bs.bdate FROM Item i JOIN (SELECT b.iid, b.bdate FROM Buys b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo "<table width=\"50%\"><tr><th align=\"left\">Item name</th><th align=\"right\">Purchase date</th></tr>";
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
  <div class = "selling">
    <h2> Auctions you are participating in</h2>
    <?php
      $usr_email=$_SESSION['email'];
      $sql = "SELECT i.iname, a1.* FROM Item i JOIN (SELECT * FROM Auction a Where a.email=\"$usr_email\") a1 ON a1.iid=i.iid";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo "<table width=\"50%\"><tr><th align=\"left\">Item name</th> <th align=\"right\">bid price</th> <th align=\"right\">end date</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["iname"]."</td> <td align=\"right\">".$row["curr_bid"]."</td> <td align=\"right\">".$row["end_date"]."</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<i>You are not participating in any auction!</i>";
      }
      //Close connection
      $conn->close();
    ?>
  </div>

  </center>
  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>

</html>
