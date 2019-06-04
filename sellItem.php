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

    // if(mysqli_query($conn, $sql) == true){
    //
    // } else {
    //   echo "cannot add new item<br>";
    //   echo mysqli_error($conn);
    // }
    // mysqli_query($conn, $sql);

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
        <h3>Sell an Item!</h3>
        <p> Please Fill the Item Information! </p>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

          <div class="form-group">
            <input type="text" class="form-control" name = "item_name" placeholder="Item Name *" required />
          </div>
          <div class="form-group">
            <input type="number" class="form-control" name = "sellprice" placeholder="Selling Price *" min="1" max="20000" required />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "category" placeholder="Category *" required />
          </div>
          <div class="form-group">
            <input type="number" class="form-control" name = "stock" placeholder="Stock *"  min="1" max="100"required />
          </div>

          <br>
          <div class="form-group">
            <input type="submit" class="btnSubmit" value="Submit" />
          </div>
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
