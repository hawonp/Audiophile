<!--
  Authors:  Hawon Park    hawon.park@stonybrook.edu
            Jeong Ho Shin jeongho.shin@stonybrook.edu
            Sujeong Youn  sujeong.youn@stonybrook.edu
-->

<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }

  // Create connection
  $servername = "localhost";
  $username = "user";
  $password = "hey";
  $dbname = "auction_db";
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  $myemail = $_SESSION['email'];
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

  <title>Audiophile</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

</head>

<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //posting discussions
    $query = "INSERT INTO Buys (email, iid, bdate) VALUES ('$myemail', $iid, CURDATE())";
    $result   = $conn->query($query);
    if (!$result) echo "INSERT failed: $query<br>" .
      $conn->error . "<br>";
    else {
      //Alert and return to item page
      $message = "Your order has been placed";
      echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";
    }
  }

  //Checking if the user has enough monnaie
  $sql = "SELECT u.credit, j1.sellprice FROM User u JOIN (SELECT i.sellprice FROM Item i WHERE i.iid=$iid) AS j1 WHERE u.email='$myemail'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["sellprice"] > $row["credit"]) {
      $message = "You have insufficient balance.";
      echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";
    }
  }
?>

<body>

  <div class="container login-container">
      <div class="login-form-1">
        <h3> Place your order </h3>
        <?php
          //Get the user and seller item
          $sql = "SELECT u.details, u.street, u.city, c.country, u.credit FROM User u, City c WHERE u.email='$myemail' AND u.city=c.city";
          $result = $conn->query($sql);

          //Get the info of the purchaser
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mycountry = $row["country"];
            $myaddress = $row["details"]. " " .$row["street"]. " " .$row["city"]. " " .$row["country"];
            $mycredit = $row["credit"];

          } else {
              echo "Somethings wrong...";
          }

          //Get the info of the item and seller
          $sql = "SELECT * FROM item i JOIN (SELECT c.country FROM city c INNER JOIN (select u.city FROM user u WHERE u.email='$seller') AS j1 ON c.city=j1.city) AS j2 WHERE i.iid='$iid';";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $theircountry = $row["country"];
            $theiname = $row["iname"];
            $subtotal = $row["sellprice"];

          } else {
              echo "Somethings wrong...";
          }
          $shippingfee = 0;
          if ($mycountry != $theircountry) {
            $shippingfee = $subtotal * 0.1;
          }
          $total = $shippingfee + $subtotal;

          //Print the stuff
          echo "<table align='center' class='table-dark table-striped'><tr><th></th> <th></th></tr>";
          echo "<tr><td style='text-align: left'>Item: </td> <td style='text-align: right'>$theiname</td></tr>";
          echo "<tr><td style='text-align: left'>Shipping address: </td> <td style='text-align: right'>$myaddress</td></tr>";
          echo "<tr><td style='text-align: left'>Subtotal: </td> <td style='text-align: right'>$subtotal ₩</td></tr>";
          echo "<tr><td style='text-align: left'>Shipping + tax fee: </td> <td style='text-align: right'>$shippingfee ₩</td></tr>";
          echo "<tr><td style='text-align: left'>Total: </td> <td style='text-align: right'>$total ₩</td></tr>";
          echo "</table>";
        ?>
        <br>
        <form method="POST" action="<?php echo "purchaseItem.php?iid=$iid&seller=$seller";?>">
          <input type="submit" class="btnSubmit" name = "submit" value="Submit" />
        </form>
      </div>
    <!-- </div> -->
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>

</html>
