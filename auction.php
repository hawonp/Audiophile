<?php
  session_start();
  if(!isset($_SESSION['email'])){
    header("Location:Login.php");
  } else {
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

  <center>
  <!-- Table of items currently on auction -->
  <div class="jumbotron emp">
    <h2 class="my-4">Welcome to Auction Page!</h2>
    <p class="lead"> Bid on the Current Auctions</p>
    <hr class="my-4">
  </div>
  </center>

  <div class="container-fluid selling">
  <?php
      $result = mysqli_query($conn, "SELECT * FROM Auction a, Item i WHERE a.iid = i.iid ORDER BY a.iid");
      $inc = 0;

      if (mysqli_num_rows($result) > 0) {

        echo "<table class = \"table table-hover\"><thread><tr><th>Item name</th> <th>Current bidder</th> <th>Current bid price (Won)</th><th>Auction end date</th><th>Bidding Amount Entry</th></tr></thread>";
        echo "<tbody>";

        while($row = mysqli_fetch_array($result)) {
          echo "<tr><td>".$row['iname']."</td><td>".$row[0]."</td><td>".$row['curr_bid']."</td><td>".$row['end_date']."</td>
          <td><form name=\"increase_bid\" method='POST' action=auction.php>
          <input type=number size=30 name=\"bid_".$inc."\"/>
          <input type=submit name=\"but_".$inc."\" value=\"RACE!\"/>
          </form></td></tr>";
          ++$inc;
        }

        echo "</tbody>";
        echo "</table>";

      } else {

        echo "0 results";

      }

      function increaseBid($conn, $input, $textinput){

        $raw_results = mysqli_query($conn, "SELECT * FROM Auction a, Item i Where a.iid = i.iid ORDER BY a.iid");
        $count = 0;

            if($input == 0){

                $results = mysqli_fetch_array($raw_results);

              if((int)$textinput<=$results['curr_bid']){

                $message = "The bidding value is too small!";
                echo "<script type='text/javascript'>alert('$message');</script>";

              } else if($textinput >= 2147483647){

                $message = "The number is too large! Please re-enter.";
                echo "<script type='text/javascript'>alert('$message');</script>";

              } else {

                  $message = "Your bidding was successful. ".$result['email'];
                  mysqli_query($conn, "UPDATE Auction SET curr_bid=".(int)$textinput." WHERE iid = ".$results["iid"]);
                  mysqli_query($conn, "UPDATE Auction SET email=\"".$_SESSION['email']."\" WHERE iid = ".$results["iid"]);
                  echo "<script type='text/javascript'>alert('$message');</script>";
                  mysqli_query($conn, "UPDATE Auction SET count = count + 1 WHERE iid = ".$results["iid"]);
              }

              } else {

              while($results = mysqli_fetch_array($raw_results)){

                if($count == $input){

                    if((int)$textinput <= $results['curr_bid']){

                        $message = "The bidding value is too small!";
                        echo "<script type='text/javascript'>alert('$message');</script>";

                    } else if($textinput >= 2147483647){

                      $message = "The number is too large! Please re-enter.";
                      echo "<script type='text/javascript'>alert('$message');</script>";

                    } else {

                      $message = "Your bidding was successful.";
                      mysqli_query($conn, "UPDATE Auction SET curr_bid=".(int)$textinput." WHERE iid = ".$results["iid"]);
                      mysqli_query($conn, "UPDATE Auction SET email=\"".$_SESSION['email']."\" WHERE iid = ".$results["iid"]);
                      echo "<script type='text/javascript'>alert('$message');</script>";
                      mysqli_query($conn, "UPDATE Auction SET count = count + 1 WHERE iid = ".$results["iid"]);

                    }
                }

                ++$count;

                }

              }


          echo ('<meta http-equiv="refresh" content="0;url='.$_SERVER['HTTP_REFERER'].'">');

        } // The end of function


      if(isset($_POST['but_0'])){
        increaseBid($conn, 0, $_POST['bid_0']);
      } else if(isset($_POST['but_1'])){
        increaseBid($conn, 1, $_POST['bid_1']);
		  } else if(isset($_POST['but_2'])){
        increaseBid($conn, 2, $_POST['bid_2']);
      } else if(isset($_POST['but_3'])){
        increaseBid($conn, 3, $_POST['bid_3']);
      }else if(isset($_POST['but_4'])){
        increaseBid($conn, 4, $_POST['bid_4']);
      }else if(isset($_POST['but_5'])){
        increaseBid($conn, 5, $_POST['bid_5']);
      }else if(isset($_POST['but_6'])){
        increaseBid($conn, 6, $_POST['bid_6']);
      }else if(isset($_POST['but_7'])){
        increaseBid($conn, 7, $_POST['bid_7']);
      }else if(isset($_POST['but_8'])){
        increaseBid($conn, 8, $_POST['bid_8']);
      }else if(isset($_POST['but_9'])){
        increaseBid($conn, 9, $_POST['bid_9']);
	  }

    mysqli_close($conn);
?>
</div>

  <div class="py-5 footer bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; HaJoSue 2019</p>
    </div>
  </div>

</body>

</html>
