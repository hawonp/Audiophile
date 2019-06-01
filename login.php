<?php
  session_start();
  $servername = "localhost";
  $username = "user";
  $password = "hey";
  $dbname = "auction_db";

  $error = "";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form
    $myusername = mysqli_real_escape_string($conn,$_POST['email']);
    $mypassword = mysqli_real_escape_string($conn,$_POST['password']);

    $sql = "SELECT email FROM User WHERE email = '$myusername' and password = '$mypassword'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    if($count == 1) {
      // session_register("myusername");
      $_SESSION['email'] = $myusername;
    }else {
      $error = "Your Login Name or Password is invalid";
      $error = "Incorrect email or password!<br>";
    }
  }
  mysqli_close($conn);

  if(isset($_SESSION["email"])) {
    header("Location:index.php");
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
  <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlignActive" href="login.php">LOGIN</a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- <hr> -->
  <center>
  <div class = "login">
    <h2> Please Login First! </h2>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      E-mail: <input type="email" name="email" value = "" required>
      Password: <input type="password" name="password" value = "" required>
      <br><br>

      <?php echo $error; ?>

      <input type="submit" class="button_primary" value="Submit">
      <input type="reset" class="button_default" value = "Reset">

      <p>Dont have an account? <a href="register.php">Sign Up here!</a>.</p>

    </form>
  </div>
  </center>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>
</html>
