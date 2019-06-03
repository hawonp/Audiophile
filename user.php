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

  <title> Audiophile </title>

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
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <a class="rightAlignActive" href="user.php">YOUR PROFILE</a>
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
          <!-- <li class="nav-item">
            <a class="nav-link" href="selling.php">Sales</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="auction.php">Auctions</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="user.php">My Page</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <center>
  <!-- User info -->
  <div class="jumbotron emp">
    <h2 class="display-4">Hello <?php echo $myfname. " ". $mylname; ?>!</h2>
    <p class="lead"> Welcome to your Profile!</p>
    <!-- <hr class="my-4"> -->
    
   <p class = "text-primary"> Email: <?php echo $myemail; ?> </p>
    <p class = "text-info"> Phone Number: <?php echo $mypnum; ?> </p>
    <!-- <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a> -->
  </div>




  <div class="container-fluid">
    <h2> Your Items!</h2>
    <div class = "selling">
      <?php
        $usr_email=$_SESSION['email'];
        $sql = "SELECT i.iname, bs.stock FROM Item i JOIN (SELECT b.iid, b.stock FROM Sells b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // echo "<div class = \"container\">"
          echo "<div class = \"container\">";
          echo "<table class = \"table table-hover\" width=\"50%\"> <thread> <tr> <th align=\"left\">Item name</th> <th align=\"right\">Stock</th></tr></thread>";

          // output data of each row
          echo "<tbody>";
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["iname"]."</td><td align=\"right\">".$row["stock"]."</td></tr>";
          }
          echo "</tbody>";
          echo "</table>";
          echo "</div class = \"container\">";
        } else {
          echo "<i>You are not selling anything right now!</i>";
        }
      ?>
      <br>
      <a class="btn btn-primary btn-lg" href="/auction/sellItem.php" role="button">Sell More Items?</a>
    </div>
  </div>

  <hr class="style1">
  <!-- Purchase History -->
  <div class="container-fluid bg-grey">
    <h2> Purchase History!</h2>

    <div class = "selling">
      <!-- retreiving data from db -->
      <?php
        $usr_email=$_SESSION['email'];
        $sql = "SELECT i.iname, bs.bdate FROM Item i JOIN (SELECT b.iid, b.bdate FROM Buys b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<div class = \"container\">";
          echo "<table class = \"table table-hover\" width=\"50%\"><thread> <tr><th align=\"left\">Item name</th><th align=\"right\">Purchase date</th></tr></thread> ";

          echo "<tbody>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["iname"]."</td><td align=\"right\">".$row["bdate"]."</td></tr>";
          }
          echo "</tbody>";
          echo "</table>";
          echo "</div class = \"container\">";
        } else {
          echo "<i>You have not bought anything yet!</i>";
        }

      ?>
    </div>

  </div>

  <hr class="style1">
  <!-- Auction history-->
  <div class = "container selling">
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
