<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
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
      Item Name: <input type="text" name="email" required>
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
