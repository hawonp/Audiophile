<?php
  session_start();
  if(isset($_SESSION["email"])) {
    $link = "logout.php";
    $text = "LOGOUT";
    // header("Location:index.php");
  }
  else {
    $link = "login.php";
    $text = "LOGIN";
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

  <!-- Navigation -->
  <div class="topnav">
    <a class="active" href="index.php">Home</a>
    <a href="auction.php">Auction</a>
    <a href="selling.php">Sales</a>
    <a class="rightAlign" href="<?php echo $link; ?>"> <?php echo $text; ?></a>
    <!-- change later if needed-->
    <a class="rightAlign" href="user.php">YOUR PROFILE</a>
  </div>

  <!-- <hr> -->

  <!-- Items on Auction  -->
  <div class="auction">
    <h1> Items on Auction! </h1>

    <table style="width:75%">
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Age</th>
      </tr>
      <tr>
        <td>Jill</td>
        <td>Smith</td>
        <td>50</td>
      </tr>
      <tr>
        <td>Eve</td>
        <td>Jackson</td>
        <td>94</td>
      </tr>
    </table>

  </div>

  <br>
  <hr>

  <!-- Items being sold -->
  <div class = "selling">

    <h1> Items on Sale! </h1>

    <table style="width:75%">
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Age</th>
      </tr>
      <tr>
        <td>Jill</td>
        <td>Smith</td>
        <td>50</td>
      </tr>
      <tr>
        <td>Eve</td>
        <td>Jackson</td>
        <td>94</td>
      </tr>
    </table>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <p>Copyright &copy; HaJoSue 2019</p>
  </div>


</body>

</html>
