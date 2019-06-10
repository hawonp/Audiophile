<!--
  Authors:  Hawon Park    hawon.park@stonybrook.edu
            Jeong Ho Shin jeongho.shin@stonybrook.edu
            Sujeong Youn  sujeong.youn@stonybrook.edu
-->

<?php
  //deletes current user's session and stored email
  session_start();
  unset($_SESSION["email"]);
  header("Location:index.php");
?>
