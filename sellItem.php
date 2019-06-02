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
    <h2> Sign Up </h2>
    <p> Please fill this form to create an account. </p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      E-mail: <input type="email" name="email" required>
      Password: <input type="password" name="password" required>
      <br><br>

      First Name: <input type="text" name="first_name" required>
      Last Name: <input type="text" name="last_name" required>
      <br><br>

      Phone Number: <input type="text" name="phone_number" required>
      <br><br>

      House/Plot Number: <input type="text" name="plot" required>
      <br><br>

      Street: <input type="text" name="street" required>
      <br><br>

      City: <input type="text" name="city" required>
      <br><br>

      Country: <input type="text" name="country" required>
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
