<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }
  //Accessing db
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
  $iid = $_GET['iid'];
  $discussion = $review = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <link href="./css/styles.css" rel="stylesheet">
  <?php
    $sql = "SELECT i.iname FROM Item i WHERE i.iid=$iid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      echo "<title>".$row["iname"]."</title>";
    } else {
      echo "SUCH ITEM DOESN'T EXIST";
    }
  ?>

  <script>
  function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }

  </script>
</head>

<body>
  <!-- Navigation -->
  <div class="topnav">
    <a href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- Item description -->
  <div class="itemDes">
  <div class="row">
    <!-- Item image -->
    <div class="itemImg">
      <?php
        echo "<img src='images/".$row["iname"].".jpg' alt='Item image'>";
      ?>
    </div>
    <!-- Item Description -->
    <div class="itemDesc">
      <h2> <?php echo $row["iname"]; ?></h2>
      <hr>
      dd
    </div>
  </div>
  </div>

  <!-- hr -->
  <hr>

  <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'posts')">Posts </button>
    <button class="tablinks" onclick="openCity(event, 'reviews')">Reviews</button>
  </div>


  <!-- Discussion -->
  <div id="posts" class="tabcontent">
    <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["discussion"])) {
          $discussion = "";
        } else {
          $discussion = $_POST["discussion"];
        }
        if (empty($_POST["review"])) {
          $review = "";
        } else {
          $review = $_POST["review"];
        }
      }
    ?>
    <h3> Discussions about this item</h3>
    <!-- Text input -->
    <div style="text-align: center">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
        <textarea name="discussion" rows="5" style="width:80%;" name="discussion"><?php echo $discussion;?></textarea>
        <input type="submit" value="Submit">
      </form>
    </div>

    <!-- posts -->
    <div style="text-align: center">
      <div class="row"> <div class="discussion"> a</div> <div class="discussion"> b</div> </div>
    </div>
  </div>
<!--
  email VARCHAR(20),
  iid INTEGER,
  thread INTEGER,
  comment_date DATE,
  comment VARCHAR(64),
    -->
  <!-- Reviews -->
  <div id="reviews" class="tabcontent">
    <h3> Reviews of this item</h3>
    <!-- Text input -->
    <div style="text-align: center">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
        <textarea name="review" rows="5" style="width:80%;" name="review"><?php echo $review;?></textarea>
        <input type="submit" value="Submit">
      </form>
    </div>

    <!-- posts -->
    <div style="text-align: center">

    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

</body>
</html>
