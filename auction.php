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

  <title>Auction</title>
</head>

<body>
  <!-- Accessing db -->
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
  ?>

  <!-- Navigation -->
  <div class="topnav">
    <a href="index.php">Home</a>
    <a class = "active" href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="logout.php">LOGOUT</a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- <hr> -->

  <h2> Items currently on auction! </h2>
  
  <!-- Table of items currently on auction -->

  <div class="selling">
    <?php

      function increaseBid($conn, $input, $textinput){
       
        $raw_results = mysqli_query($conn, "SELECT * FROM Auction a, Item i Where a.iid = i.iid"); 
        $count = 0;

        if($input == 0){
          $results = mysqli_fetch_array($raw_results);
          mysqli_query($conn, "UPDATE Auction SET curr_bid = ".(int)$textinput." WHERE iid = ".$results["iid"]);
        } else {
          while($results = mysqli_fetch_array($raw_results)||$count<=$input){
            if($count == $input){
              mysqli_query($conn, "UPDATE Auction SET curr_bid = ".(int)$textinput." WHERE iid = ".$results["iid"]);
            }
          }
        }
      }
    
      $result = mysqli_query($conn, "SELECT * FROM Auction a, Item i WHERE a.iid = i.iid");
      $numRow = mysqli_num_rows($result);
      $inc = 0;

      if ($numRow > 0) {
        
        echo "<table><tr><th>Item name</th> <th>Current bidder</th> <th>Current bid price</th><th>Auction end date</th><th>Bidding Amount Entry</th></tr>";
        
        while($row = mysqli_fetch_array($result)) {
          echo "<tr><td>".$row['iname']."</td><td>".$row['email']."</td><td>".$row['curr_bid']."</td><td>".$row['end_date']."</td>
          <td><form name = \"increase_bid\" method = 'POST' action = auction.php>
          <input type=text size=30 name=\"bid_".$inc."\"/>
          <input type=submit name = \"but_".$inc."\" value=\"RACE!\"/>
          </form></td></tr>";
          ++$inc;
        }

        echo "</table>";
      
      } else {
      
        echo "0 results";
      
      }

      if(isset($_POST['but_0'])){
        increaseBid($conn, 0, $_POST['bid_0']);
      } else if(isset($_POST['but_1'])){
        increaseBid($conn, 1, $_POST['bid_1']);
      } else if(isset($_POST['but_2'])){
        increaseBid($conn, 2, $_POST['bid_2']);
      }else if(isset($_POST['but_3'])){
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

    ?>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
