<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }

  // echo mt_rand();

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

    if(mysqli_query($conn, $sql) == true){

    } else {
      echo "cannot add new item<br>";
      echo mysqli_error($conn);
    }
    // mysqli_query($conn, $sql);

    header("Location:user.php");
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
  <link href="./css/login.css" rel="stylesheet">
  <link href="./css/button.css" rel="stylesheet">


  <title>Auction</title>
</head>

<body>

  <!-- Navigation -->
  <!-- <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div> -->

  <!-- <hr> -->

  <center>
  <div class = "login">
    <h2> Sell an Item! </h2>
    <p> Please fill the item information! </p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Item Name: <input type="text" name="item_name" required>
      Sellprice: <input type="number" name="sellprice" min="1" max="20000">
      <br>

      <p> The Minimum Bid to enter an auction on this item will be 75% of the sellprice! </p>

      <!-- Category: <input type="text" list="categories" />
      <datalist id="categories">
        <option value="headphones">
        <option value="earphones">
      </datalist> -->
      Category: <input type="text" name="category" required>
      Stock: <input type="number" name="stock" min="1" max="100">

      <br><br>

      <input type="submit" class="button_primary" value="Submit">
      <input type="reset" class="button_default" value = "Reset">

      <br><br>

      <button class= "button_return" onclick="goBack()">Go Back</button>
      <script>
        function goBack() {
          window.history.back();
        }
      </script>

    </form>

  </div>
  </center>


  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
