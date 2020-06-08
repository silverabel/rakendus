<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");
  require("classes/Session.class.php");

  SessionManager::sessionStart("vr20", 0, "/~silver.abel/", "tigu.hk.tlu.ee");

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

  $response = loeÕpilogi();
  

?>

<!DOCTYPE html>
<html lang="et">
  <head>
  <meta charset="utf-8">
  <title>Veebirakendused ja nende loomine 2020</title>
  <style>
    table tr td {
      border: 1px solid;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <th>Kasutaja</th>
      <th>Õppeaine</th>
      <th>Tegevus</th>
      <th>Aeg tundides</th>
      <th>Lisatud</th>
    </tr>
    <?php echo $response; ?>
  </table>
  <p><a href="home.php">Esileht</a></p>
  <p><a href="addNews.php">Lisa uudiseid</a></p>
  <p><a href="showNews.php">Vaata uudiseid</a></p>
  <p><a href="photoUpload.php">Lisa pilte</a></p>
  <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
  <p><a href="õpilogi.php">Täida õpilogi</a></p>
  
  
  <p>Logi <a href="?logout=1">välja</a></p>
</body>
</html>