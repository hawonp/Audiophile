<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  }

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

  $mycat = "";
  if(isset($_GET['cat'])){
    $mycat = $_GET['cat'];
  }
  // $mycat = $_GET['cat'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="whatever">
  <meta name="author" content="whatever">

  <!-- <link href="./css/styles.css" rel="stylesheet"> -->

  <title>Audiophile</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">


</head>

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
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="selling.php">Sales</a>
          </li> -->
          <li class="nav-item">
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

  <!-- <hr> -->

  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <div class="col-lg-3">

        <h1 class="my-4">Shop Name</h1>
        <div class="list-group">
          <a href="index.php?cat=Earphones" class="list-group-item">Earphones</a>
          <a href="index.php?cat=Headphones" class="list-group-item">Headphones</a>
          <a href="index.php?cat=Speakers" class="list-group-item">Speakers</a>
          <a href="index.php?cat=Media_Players" class="list-group-item">Media Players</a>
          <a href="index.php?cat=Amplifiers" class="list-group-item">Amplifiers</a>
          <a href="index.php?cat=Accessories" class="list-group-item">Accessories</a>
          <a href="index.php" class="list-group-item">All</a>
        </div>

      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">
<!-- SELECT email, iid, SUM(rating) as score FROM Review GROUP BY iid ORDER BY score DESC LIMIT 3; -->
        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
          <ol class="carousel-indicators">

            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
            </div>
            <div class="carousel-item">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
            </div>
            <div class="carousel-item">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>

        <div class="row">

          <?php
            if($mycat == ""){
              $sql = "SELECT * FROM Sells S, Item I, Item_To_Subcategory C Where S.iid = I.iid AND I.iname = C.iname";
            } else {
              $sql = "SELECT i.iid, i.iname, s2.subcategory, s2.category, i.sellprice FROM Item i INNER JOIN (SELECT s1.category, s1.subcategory, i1.iname FROM Item_To_Subcategory I1 INNER JOIN (SELECT * FROM Subcategory_To_Category sc WHERE sc.category='$mycat') AS s1 ON s1.subcategory=I1.subcategory) AS s2 ON i.iname=s2.iname WHERE i.iid IN (SELECT s.iid FROM Sells s)";
              // $sql = "SELECT I.iid, I.iname, I.sellprice, S.subcategory FROM Sells S, Item I, Item_To_Subcategory S, Subcategory_To_Category Where S.iid = I.iid AND I.iname = S.iname AND C.category='$mycat'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<div class = \"col-lg-4 col-md-6 mb-4\">";
                  echo "<div class=\"hey card h-100\">";
                    echo "<img class=\"restrict card-img-top\" src='images/".$row["iid"].".jpg' alt=\"Image Does Not Exist!\">";
                    echo "<div class=\"card-body\">";
                      echo "<h4 class=\"card-title\">";
                      echo "<a href='item.php?iid=".$row["iid"]."'>".$row["iname"]."</a></h4>";
                      echo "<h5>".$row["sellprice"]." WON</h5>";
                    echo "</div>";
                    echo "<div class=\"card-footer\"> <small class=\"text-muted\">".$row["subcategory"]."</small> </div>";
                  echo "</div>";
                echo "</div>";
              }

            } else {
                echo "<p> No Items Being Sold! </p>";
            }
          ?>

        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; HaJoSue 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>
