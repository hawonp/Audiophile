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
  $rating;

  if(isset($_POST['liked'])){
    $myemail = $_SESSION['email'];
    // echo "hello";
    // $sql = "UPDATE User SET credit = '$mynum' WHERE email='$myemail'";
    $sql = "INSERT INTO Likes(email, iid) VALUES('$myemail', '$iid')";
    mysqli_query($conn, $sql);

    $message = "You have liked this item!";
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <title> Audiophile </title>

  <link href="./css/styles.css" rel="stylesheet">

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

  <style>
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      color: white;
      text-align: center;
    }
  </style>
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
</head>
<!-- php input handlers -->
<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //posting discussions
    if (empty($_POST["discussion"])) {
      $discussion = "";
    } else {
      $discussion = $_POST["discussion"];
      $query = "INSERT INTO Discussion (email, iid, comment_date, comment) VALUES" ."('".$_SESSION['email']."', $iid, CURDATE(), '$discussion')";
      $result   = $conn->query($query);
      if (!$result) echo "INSERT failed: $query<br>" .
        $conn->error . "<br><br>";
    }
    //posting reviews
    if (empty($_POST["review"]) || empty($_POST["rating"])) {
      $review = "";
    } else {
      $review = $_POST["review"];
      $rating = $_POST["rating"]; echo $rating;
      $query = "INSERT INTO Review (email, iid, rating, rcontent) VALUES" ."('".$_SESSION['email']."', $iid, $rating, '$review')";
      $result   = $conn->query($query);
      if (!$result) {
        $message = "You have already reviewed this product!";
        echo "<script type='text/javascript'>alert('$message');</script>";
      }
    }
  }
?>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">Audiophile</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="auction.php">Auctions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user.php">My Page</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Item description -->
  <div class="itemDes">
    <div class="row">
      <!-- Item image -->
      <div class="itemImg">
        <?php
          echo "<img src='images/".$iid.".jpg' alt='Item image'>";
        ?>
      </div>
      <!-- Ite  m Description? -->
      <div class="itemDesc">
        <h2> <?php echo $row["iname"]; ?></h2>
        <hr>
        <?php
          $sql = "SELECT AVG(r.rating) AS avgRating FROM Review r WHERE r.iid=$iid";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
            echo "Rating: ".round($row["avgRating"], 1)."/5";
          } else {
            echo "Rating: N/A";
          }
          echo "<br>";
          echo "<br>";

          //Get stock number
          $sql = "SELECT * FROM Item s JOIN (SELECT COUNT(*) FROM Likes l WHERE l.iid=$iid) AS j1 WHERE s.iid=$iid";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["stock"] > 0) {
              echo "Stock: ".$row["stock"];
              echo "<br><br>";
              echo "Likes: ".$row["COUNT(*)"];
              echo "<br>";
              echo "<form method=\"POST\">";
              echo "<input type=\"hidden\" name=\"liked\">";
              echo "<button class = \"btn btn-primary btn-sm\" type = \"submit\"> Like </button>";
              echo "</form>";

              echo "<a class=\"btn btn-primary btn-sm\" href='purchaseItem.php?iid=$iid&seller=".$row["email"]."' role=\"button\">Buy this item</a>";
            } else {
              echo "Out of stock";
            }
          } else {
            echo "Something went wrong";
          }
        ?>
      </div>
    </div>
  </div>

  <!-- hr -->
  <hr>

  <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'posts')" id="defaultOpen"> Discussions </button>
    <button class="tablinks" onclick="openCity(event, 'reviews')">Reviews</button>
  </div>

  <!-- Discussion -->
  <div id="posts" class="tabcontent">
    <h3> Discussions about this item</h3>
    <!-- Text input -->
    <div style="text-align: center">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
        <textarea name="discussion" rows="7" style="width:80%;" name="discussion " placeholder="Enter discussion here" maxlength="256" required></textarea>
        <input type="submit" value="Submit">
      </form>
    </div>

    <!-- posts -->
    <div style="text-align: center">
      <?php
        $sql = "SELECT * FROM Discussion d WHERE d.iid=$iid ORDER BY d.thread ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<table class='commentTable table-striped' align='center'><tr><th class='discussionContent'></th></tr>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td class='discussionContent'>".$row["email"]." | ".$row["comment_date"]." <br> ".$row["comment"]."</td></tr>";
          }
          echo "</table>";
        } else {
          echo "Quiet... It's too quiet here";
        }
      ?>
    </div>
  </div>

  <!-- Reviews -->
  <div id="reviews" class="tabcontent">
    <h3> Reviews of this item</h3>
    <!-- Text input -->
    <div style="text-align: center">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
        <textarea name="review" rows="7" style="width:70%;" name="review" placeholder="Enter review here" maxlength="256" required ></textarea>Rating : <input type="number" name="rating" value="5"max="5" required>
        <input type="submit" value="Submit">
      </form>
    </div>

    <!-- posts -->
    <div style="text-align: center">
      <?php
        $sql = "SELECT * FROM Review r WHERE r.iid=$iid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<table class='commentTable table-striped' align='center'><tr><th class='discussionContent'></th></tr>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td class='discussionContent'>".$row["email"]." | rating: ".$row["rating"]."/5 <br> ".$row["rcontent"]."</td></tr>";
          }
          echo "</table>";
        } else {
          echo "Quiet... It's too quiet here";
        }
      ?>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>

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

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

  </script>

</body>
</html>
