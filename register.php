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

#GENERATE UNIQUE AID EVERY TIME
function get_count(){
  static $count = 2;
  return $count++;
}

// CHECK CREDENTIALS
if($_SERVER["REQUEST_METHOD"] == "POST") {
  // echo "hey";

  //VALIDATE EMAIL
  $sql = "SELECT email FROM User WHERE email = \"".$_POST["email"]."\"";

  //attempt to execute the prepared
  if ($res = mysqli_query($conn, $sql)){
    // echo "hey";
    if (mysqli_num_rows($res) > 0) { //if there already exists a product with the specifications
      echo "WARNING: This email is already taken! <br>";
    }
    else {
      $myemail = trim($_POST["email"]);
      echo $myemail;
    }
  }
  else {
    echo "Error message = ".mysqli_error($conn);
  }

  //PREPARE TO ADD NEW USER
  $mypassword = $_POST["password"];
  $myfname = $_POST["first_name"];
  $mylname = $_POST["last_name"];
  $mypnum = $_POST["phone_number"];
  $mydetails = $_POST["plot"];
  $mystreet = $_POST["street"];
  $mycity = $_POST["city"];
  $mycountry = $_POST["country"];

  echo substr($mypnum, 0, 5);

  #ENTER NEW ADDRESS
  $sql = "SELECT country FROM City WHERE city = \"".$_POST["city"]."\"";
  if ($res = mysqli_query($conn, $sql)){
    if (mysqli_num_rows($res) > 0) { //if there already exists a product with the specifications
      echo "Skip adding new city tuple";
    }
    else {
      $sql2 = "INSERT INTO City(city, country)
        VALUES (\"".$_POST["city"]."\", \"".$_POST["country"]."\")";

      if (mysqli_query($conn, $sql2) == true) {
        echo "New City tuple Added!<br>";
      } else {
        echo "Could not add new city = ".mysqli_error($conn);
      }
    }
  }
  else {
    echo "Error message = ".mysqli_error($conn);
  }

  #ENTER NEW ADDRESS
  $sql = "INSERT INTO Address(aid, details, street, city)
    VALUES(substr($mypnum, 0, 5), \"".$_POST["plot"]."\", \"".$_POST["street"]."\", \"".$_POST["city"]."\")";

  if (mysqli_query($conn, $sql) == true) {
    echo "New address Added!<br>";
  }
  else {
    echo "Unable to add new address!\n".mysqli_error($conn);
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
  <link href="./css/button.css" rel="stylesheet">

  <title>Auction</title>
</head>

<body>

  <!-- Navigation -->
  <!-- <div class="topnav">
    <a href="index.php">Home</a>
    <a href="#auction">Auction</a>
    <a href="#contact">Contact</a>
    <a class="rightAlign" href="#login">LOGIN</a>
  </div> -->

  <!-- login screen -->
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

      <p>Already have an account? <a href="login.php">Login here</a>.</p>

    </form>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
