<?php
$servername = "localhost";
$username = "user";
$password = "hey";
$dbname = "auction_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CHECK CREDENTIALS
if($_SERVER["REQUEST_METHOD"] == "POST") {

  //VALIDATE EMAIL
  $sql = "SELECT email FROM User WHERE email = \"".$_POST["email"]."\"";

  //attempt to execute the prepared
  if ($res = mysqli_query($conn, $sql)){
    // echo "hey";
    if (mysqli_num_rows($res) > 0) { //if there already exists a product with the specifications
      // echo "WARNING: This email is already taken! <br>";
      $link = "WARNING: This email is already taken!";
    }
    else {
      #ENTER NEW CITY
      $sql = "SELECT country FROM City WHERE city = \"".$_POST["city"]."\"";
      if ($res = mysqli_query($conn, $sql)){
        if (mysqli_num_rows($res) > 0) { //if there already exists a product with the specifications
          echo "Skip adding new city tuple";
        }
        else {
          $sql2 = "INSERT INTO City(city, country)
            VALUES (\"".$_POST["city"]."\", \"".$_POST["country"]."\")";

          if (mysqli_query($conn, $sql2) == true) {
            // echo "New City tuple Added!<br>";
          } else {
            echo "Could not add new city = ".mysqli_error($conn);
          }
        }
      }
      else {
        echo "Error message = ".mysqli_error($conn);
      }

      #ENTER NEW USER
      $sql = "INSERT INTO User(first_name, last_name, password, email, phone_num, details, street, city)
        VALUES( \"".$_POST["first_name"]."\",
                \"".$_POST["last_name"]."\",
                \"".$_POST["password"]."\",
                \"".$_POST["email"]."\",
                \"".$_POST["phone_number"]."\",
                \"".$_POST["plot"]."\",
                \"".$_POST["street"]."\",
                \"".$_POST["city"]."\")";

      if (mysqli_query($conn, $sql) == true) {
        // echo "New user Added!<br>";
      }
      else {
        echo "Unable to add new user!\n".mysqli_error($conn);
      }
      header("Location:login.php");
    } //this is the closing bracket
  }
  //failure to check for email
  else {
    echo "Error message = ".mysqli_error($conn);
  }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/login.css" rel="stylesheet">
  <!-- <link href="./css/button.css" rel="stylesheet">
  <link href="./css/styles.css" rel="stylesheet"> -->

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
        <h3>Sign Up!</h3>

        <p> Please fill this form to create an account. </p>

        <div class = "error">
          <?php echo $error; ?>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

          <div class="form-group">
            <input type="text" class="form-control" name = "email" placeholder="Your Email *" value="" required />
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name = "password" placeholder="Your Password *" value="" required />
          </div>

          <br>
          <div class="form-group">
            <input type="text" class="form-control" name = "first_name" placeholder="First Name *" value="" required />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "last_name" placeholder="Last Name *" value="" required />
          </div>
          <div class="form-group">
            <input type="tel" class="form-control" name = "phone_number" placeholder="Phone Number *" value="" required />
          </div>

          <br>
          <div class="form-group">
            <input type="text" class="form-control" name = "plot" placeholder="House Number *" value="" required />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "street" placeholder="Street *" value="" required />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "city" placeholder="City *" value="" required />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name = "country" placeholder="Country *" value="" required />
          </div>

          <div class="form-group">
            <input type="submit" class="btnSubmit" value="Submit" />
          </div>
          <div class="form-group">
            <a href="login.php" class="btnForgetPwd" value="register">Login Here</a>
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
