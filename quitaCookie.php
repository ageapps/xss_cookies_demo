<?php
session_start();

if (isset($_SESSION["usuario"])){
  $_SESSION = array();
  session_destroy();
} 
header("Location: ./index.php");
?>
