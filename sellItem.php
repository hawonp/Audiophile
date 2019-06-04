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

    // INSERT NEW Subcategory_To_Category TUPLE
    // WILL REJECT ACTION IF DUPLICATE
    $mycategory = $_POST['category'];
    $mysubcategory = $_POST['sub_category'];

    if($mycategory == ""){
      $message = "Please Select a Category!";
      echo "<script type='text/javascript'>alert('$message');</script>";
    }

    $sql = "INSERT INTO Subcategory_To_Category(subcategory, category)
      VALUES('$mysubcategory','$mycategory')";

    mysqli_query($conn, $sql);

    // INSERT NEW Item_To_Subcategory TUPLE
    // WILL REJECT ACTION IF DUPLICATE
    $myiname = $_POST['item_name'];
    $sql = "INSERT INTO Item_To_Subcategory(iname, subcategory)
      VALUES('$myiname', '$mysubcategory')";

    mysqli_query($conn, $sql);
    // if(mysqli_query($conn, $sql) == false){
    //   echo mysqli_error($conn);
    // }

    //INSERT NEW ITEM TUPLE
    $mystock = $_POST['stock'];
    $myemail = $_SESSION['email'];

    $sql = "INSERT INTO Item(iname, sellprice, email, stock)
      VALUES('$myiname', '$mysellprice', '$myemail', '$mystock')";

    mysqli_query($conn, $sql);

    // echo "success!";
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
            <input type="number" class="form-control" name = "stock" placeholder="Stock *"  min="1" max="100"required />
          </div>
          <div class="form-group">
            <select class = "form-control" name="category" required>
              <option value="">--Please choose an Category--</option>
              <option value="Accessories">Accessories</option>
              <option value="Amplifiers">Amplifiers</option>
              <option value="Earphones">Earphones</option>
              <option value="Headphones">Headphones</option>
              <option value="Media_Players">Media Players</option>
              <option value="Speakers">Speakers</option>
            </select>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "sub_category" placeholder="Subcategory  *" required />
          </div>
          <br>
          <div class="form-group">
            <input type="submit" class="btnSubmit" name = "submit" value="Submit" />
          </div>
          <div class="form-group">
            <br>
            <input type="button" class="btnSubmit" value="Return to previous page" onClick="javascript:history.go(-1)" />
          </div>
        </form>

      </div>
    <!-- </div> -->
  </div>

  <!-- FOOTER -->
  <!-- <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div> -->

</body>

</html>
