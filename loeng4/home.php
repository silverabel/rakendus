<?php
  require("../../../../configuration.php");
  require("classes/Session.class.php");

  SessionManager::sessionStart("vr20", 0, "/~silver.abel/", "tigu.hk.tlu.ee");

  //session_start();
  var_dump($_SESSION);

  //kas on sisse loginud
  if (!isset($_SESSION["userid"])) {
    //jõuga avalehele
    header("Location: page.php");
  }
    
  //välja logimine
  if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: page.php");
  }

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Veebirakendused ja nende loomine 2020</title>
  <style>
    
  </style>
</head>
<body>
  <h1>Koduleht</h1>
  <p>
    Tere <?php echo $_SESSION["userFirstname"] . " " .  $_SESSION["userLastname"]; ?>
  </p>
  
  <p>Logi <a href="?logout=1">välja</a></p>
  

</body>
</html>