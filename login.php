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
        <h3>Please Login First!</h3>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

          <div class="form-group">
            <input type="text" class="form-control" name = "email" placeholder="Your Email *" value="" required />
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name = "password" placeholder="Your Password *" value="" required />
          </div>

          <div class = "error">
            <?php echo $error; ?>
          </div>

          <br>
          <div class="form-group">
            <input type="submit" class="btnSubmit" value="Login" />
          </div>
          <div class="form-group">
            <a href="register.php" class="btnForgetPwd" value="register">Sign Up?</a>
          </div>
        </form>

      </div>
    <!-- </div> -->
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>
</html>
