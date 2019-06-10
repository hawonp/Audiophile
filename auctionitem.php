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

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer = $_GET['buy'];

    //Checking if the buyer has enough money
    $sql = "SELECT U.credit, A.curr_bid FROM User u, Auction A WHERE u.email =A.email AND A.email='$buyer'";
    // $sql = "SELECT u.credit, j1.sellprice FROM User u JOIN (SELECT i.sellprice FROM Item i WHERE i.iid=$iid) AS j1 WHERE u.email='$myemail'"
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $total = $row['curr_bid'];

      //if the buyer has insufficient money, reject transaction
      if ($row["curr_bid"] <= $row["credit"]) {
        $sql2 = "UPDATE User Set User.credit = User.credit + '$total' WHERE User.email = '$myemail'";
        mysqli_query($conn,$sql2);

        $sql2 = "UPDATE User Set User.credit = User.credit - '$total' WHERE User.email = '$buyer'";
        mysqli_query($conn,$sql2);

        $sql2 = "UPDATE Item SET Item.stock = Item.stock -1 WHERE Item.iid = '$iid'";
        mysqli_query($conn,$sql2);

        $message = "Transaction sucessful!";
        // echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";

      //buyer has sufficient money, accept transaction
      } else {
        $message = "Buyer has insufficient balance. Auction closed without transaction.";
        // echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";
      }

      //close auction regardless of buyer having sufficient money or not
      $sql2 = "DELETE FROM Auction where email= '$buyer' AND iid='$iid'";
      $result = mysqli_query($conn,$sql2);

      //redirect back to item page
      echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";
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

  <link href="./css/login.css" rel="stylesheet">

  <title>Audiophile</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

</head>

<body>

  <div class="container login-container">
    <!-- <div class="row"> -->
      <div class="login-form-1">
        <h3> Finish This Auction </h3>
        <?php
          //Get the user and owener item
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

          //get info on who is the most current bidder and their bid amount
          $sql = "SELECT I.iname, A.curr_bid, A.email AS buyer FROM Item I, Auction A WHERE I.iid = A.iid AND I.email='$myemail'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $buyer = $row['buyer'];
            $subtotal = $row["curr_bid"];
            $theiname = $row["iname"];
          }

          //Get address of buyer
          $sql = "SELECT u.details, u.street, u.city, c.country, u.credit FROM User u, City c WHERE u.email='$buyer' AND u.city=c.city";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $theircountry = $row["country"];
            $theiraddress = $row["details"]. " " .$row["street"]. " " .$row["city"]. " " .$row["country"];

          } else {
              echo "Somethings wrong...";
          }

          //calculate any shipping fees if neccessary
          $shippingfee = 0;
          if ($mycountry != $theircountry) {
            $shippingfee = $subtotal * 0.1;
          }
          $total = $shippingfee + $subtotal;

          //check if this item is on auction
          $sql = "SELECT COUNT(*) AS exist FROM Auction where iid='$iid'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $exists = $row['exist'];
          }

          //item is not on auction
          if($exists == 0){
            $message = "This item is not on auction right now!";
            echo "<script>alert('$message');window.location.href='item.php?iid=$iid';</script>";
          }
          //there is no current bidder
          else if($myemail == $buyer){
            echo "<p class = \"error\"> No one has bid on your item!</p>";
          }
          //display transaction fees (successful closure)
           else {
            //Print the stuff
            echo "<table align='center' class='table-dark table-striped'><tr><th></th> <th></th></tr>";
            echo "<tr><td style='text-align: left'>Auctioned Item: </td> <td style='text-align: right'>$theiname</td></tr>";
            echo "<tr><td style='text-align: left'>Shipping address: </td> <td style='text-align: right'>$theiraddress</td></tr>";
            echo "<tr><td style='text-align: left'>Subtotal: </td> <td style='text-align: right'>$subtotal ₩</td></tr>";
            echo "<tr><td style='text-align: left'>Shipping + tax fee: </td> <td style='text-align: right'>$shippingfee ₩</td></tr>";
            echo "<tr><td style='text-align: left'>Your Gain: </td> <td style='text-align: right'>$total ₩</td></tr>";
            echo "</table>";
          }
          echo $buyer;

        ?>
        <br>
        <form method="POST" action="<?php echo "auctionitem.php?iid=$iid&buy=$buyer";?>">
          <input type="submit" class="btnSubmit" name = "submit" value="Close Auction" />
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
