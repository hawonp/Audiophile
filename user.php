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
    $row = $result->fetch_assoc();

    $myfname = $row['first_name'];
    $mylname = $row['last_name'];
    $mypnum = $row['phone_num'];

    if(isset($_POST['balance'])){
      $mynum = $_POST['balance'];
      $sql = "UPDATE User SET credit = credit + '$mynum' WHERE email='$myemail'";
      mysqli_query($conn, $sql);
    }
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

  <title> Audiophile </title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
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
  <div class="jumbotron">
    <h2 class="display-4">Hello <?php echo $myfname. " ". $mylname; ?>!</h2>
    <p class="lead"> Welcome to your Profile!</p>
    <hr class="my-4">


   <p class = "text-primary"> Email: <?php echo $myemail; ?> </p>
    <p class = "text-info"> Phone Number: <?php echo $mypnum; ?> </p>
  </div>

  <div class ="container-fluid">
    <div class="row">
      <div class="col col-lg-4">
        <h2> Notifications! </h2>
        <?php
          $usr_email=$_SESSION['email'];
          $sql = " SELECT N.iid, N.ncontent, I.iname FROM Notification N, Item I WHERE N.email='$usr_email' AND N.iid = I.iid;";
          // $sql = "SELECT iid, ncontent FROM Notification WHERE email='$usr_email' ORDER BY nnumber DESC LIMIT 3;";
          $result = $conn->query($sql);
          while($row = $result->fetch_assoc()){
            echo "<h6 style=\"text-align : left\"><i>(".$row['iname'].") : ".$row['ncontent']."</i></h6>";
            // echo "<h6><i> ID (".$row['iid']."</i><i>) ".$row['ncontent']."</i></h6>";
            echo "<br>";

          }
        ?>
      </div>
      <div class="col col-lg-4">
        <h2> Your Balance! </h2>
        <?php
          $usr_email=$_SESSION['email'];
          $sql = "SELECT credit FROM User WHERE email = '$usr_email'";
          $result = $conn->query($sql);
          $row = $result->fetch_assoc();
          echo "<h4><i>".$row['credit']." WON </i></h4>";
        ?>
      </div>
      <div class="col-lg-4">
        <h5> Recharge Your Balance? </h5>
        <form  method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <input type="number" class="form-control" name = "balance" placeholder="Enter Balance *" min="1" required />
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <hr class="style1">
  <div class="container-fluid">
    <h2> Your Items!</h2>
    <div class = "selling">
      <?php
        $usr_email=$_SESSION['email'];
        $sql = "SELECT I.iname, I.sellprice, I.iid, I.stock, R.subcategory FROM Item I INNER JOIN Item_To_Subcategory R WHERE I.email = '$usr_email' and R.iname = I.iname";
        // $sql = "SELECT i.iname, i.iid, i.sellprice, i.stock FROM Item i WHERE i.email='$usr_email'";
        // $sql = "SELECT i.iname, bs.stock FROM Item i JOIN (SELECT b.iid, b.stock FROM Sells b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // echo "<div class = \"container\">"
          echo "<div class = \"container\">";
          echo "<table class = \"table table-hover\" width=\"50%\"> <thread> <tr> <th align=\"left\">ID</th> <th> Name </th>  <th> Price </th> <th>Stock</th> <th>Type</th></tr></thread>";

          // output data of each row
          echo "<tbody>";
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["iid"]."</td><td><a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</a></td><td>".$row["sellprice"]." won</td><td>".$row["stock"]."</td><td>".$row["subcategory"]."</td></tr>";
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
    <h2> Items You've Liked!</h2>

    <div class = "selling">
      <!-- retreiving data from db -->
      <?php
        $usr_email=$_SESSION['email'];
        $sql = " SELECT I.iid, I.iname FROM Item I INNER JOIN Likes L WHERE L.email='$usr_email' AND L.iid = I.iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<div class = \"container\">";
          echo "<table class = \"table table-hover\" width=\"50%\"><thread> <tr><th align=\"left\">Item name</th></tr></thread> ";

          echo "<tbody>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</a></td></tr>";
          }
          echo "</tbody>";
          echo "</table>";
          echo "</div class = \"container\">";
        } else {
          echo "<i>You have not liked anything yet!</i>";
        }

      ?>
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
        $sql = "SELECT i.iid, i.iname, bs.bdate FROM Item i JOIN (SELECT b.iid, b.bdate FROM Buys b Where b.email=\"$usr_email\") bs ON bs.iid=i.iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<div class = \"container\">";
          echo "<table class = \"table table-hover\" width=\"50%\"><thread> <tr><th align=\"left\">Item name</th><th align=\"right\">Purchase date</th></tr></thread> ";

          echo "<tbody>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</a></td><td align=\"right\">".$row["bdate"]."</td></tr>";
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
        echo "<table class = \"table table-hover\" width=\"50%\"><thread><tr><th align=\"left\">Item name</th> <th align=\"right\">bid price</th> <th align=\"right\">end date</th></tr></thread> ";
        echo "<tbody>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<tr><td><a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</a></td> <td align=\"right\">".$row["curr_bid"]."</td> <td align=\"right\">".$row["end_date"]."</td></tr>";

          // echo "<tr><td>".$row["iname"]."</td> <td align=\"right\">".$row["curr_bid"]."</td> <td align=\"right\">".$row["end_date"]."</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
      } else {
        echo "<i>You are not participating in any auction!</i>";
      }
      //Close connection
      $conn->close();
    ?>
  </div>

  </center>

  <br>

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; HaJoSue 2019</p>
    </div>
    <!-- /.container -->
  </footer>

</body>

</html>
