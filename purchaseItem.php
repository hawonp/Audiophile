
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

  // INSERT NEW ITEM INTO RESPECTIVE TABLES
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // INSERT NEW Sellprice_To_Bid TUPLE
    // WILL REJECT ACTION IF DUPLICATE
    $mysellprice = $_POST['sellprice'];
    $myminbid = $mysellprice * 0.75;

    $sql = "INSERT INTO Sellprice_To_Bid(sellprice, minbid)
      VALUES('$mysellprice' , '$myminbid')";

    mysqli_query($conn, $sql);

    // INSERT NEW Item_To_Category TUPLE
    // WILL REJECT ACTION IF DUPLICATE
    $myiname = $_POST['item_name'];
    $mycategory = $_POST['category'];

    $sql = "INSERT INTO Item_To_Category(iname, category)
      VALUES('$myiname', '$mycategory')";

    mysqli_query($conn, $sql);

    // INSERT NEW Item tuple
    $myitemid = mt_rand();
    $mystock = $_POST['stock'];

    $sql = "INSERT INTO Item(iid, iname, sellprice)
      VALUES('$myitemid', '$myiname', '$mysellprice')";

    mysqli_query($conn, $sql);

    // INSERT NEW SELLS TUPLE
    $myemail = $_SESSION['email'];

    $sql = "INSERT INTO Sells(email, iid, stock)
      VALUES('$myemail', '$myitemid', '$mystock')";

    if(mysqli_query($conn, $sql) == true){
      // echo "added sell tuple success!";
    } else {
      echo "cannot add new item<br>";
      echo mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location:item.php?iid=$iid");
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

<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //posting discussions
    if (empty($_POST["discussion"])) {
      $discussion = "";
    } else {
      $discussion = $_POST["discussion"];
      $query = "INSERT INTO Discussion (email, iid, comment_date, comment) VALUES" ."('".$_SESSION['email']."', $iid, CURDATE(), '$discussion')";
      $result   = $conn->query($query);
      if (!$result) echo "INSERT failed: $query<br>" .
        $conn->error . "<br><br>";
    }

    $result->close();
    $conn->close();
  }
?>

<body>

  <div class="container login-container">
    <!-- <div class="row"> -->
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
          $sql = "SELECT ";
          $result = $conn->query($sql);
          
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mycountry = $row["country"];
            $myaddress = $row["details"]. " " .$row["street"]. " " .$row["city"]. " " .$row["country"];
            $mycredit = $row["credit"];
          
          } else {
              echo "Somethings wrong...";
          }

          //Print the stuff
          echo "Item name here and price here";
          echo "Shipping address: " .$myaddress;
          echo "subtotal";
          echo "Shipping Fee";
          echo "Total";

          echo "place order";
        ?>
      </div>
    <!-- </div> -->
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
