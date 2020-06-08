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

  $privaatsus = 3;
  if (isset($_GET["privaatsus"])) $privaatsus = $_GET["privaatsus"];

  $lehenumber = 1;
  if (isset($_GET["lehenumber"])) $lehenumber = $_GET["lehenumber"];

  $response = loePildigalerii($lehenumber, $privaatsus);

  $eelmineLehekülg = 'Eelmine lehekülg';
  if ($lehenumber > 1) {
    $eelmineLehekülg = '<a id ="eelmineLK" href="?lehenumber=' . ($lehenumber - 1);
    if (isset($_GET["privaatsus"])) $eelmineLehekülg .= '&privaatsus=' . $privaatsus;
    $eelmineLehekülg .= '">Eelmine lehekülg</a>';
  }

  $järgmineLehekülg = "Järgmine lehekülg";
  if ($response[1] == 4) {
    $järgmineLehekülg = '<a href="?lehenumber=' . ($lehenumber + 1);
    if (isset($_GET["privaatsus"])) $järgmineLehekülg .= '&privaatsus=' . $privaatsus;
    $järgmineLehekülg .= '">Järgmine lehekülg</a>';
  }





  $privaatsusHTML = "<span>Privaatsus:</span> \n\t";

  if ($privaatsus == 1) $privaatsusHTML .= "<span>Avalik</span> \n\t";
  else $privaatsusHTML .= '<span><a href="?privaatsus=1">Avalik</a></span>' . "\n\t";

  if ($privaatsus == 2) $privaatsusHTML .= "<span>Sisseloginud kasutajatele</span> \n\t";
  else $privaatsusHTML .= '<span><a href="?privaatsus=2">Sisseloginud kasutajatele</a></span>' . "\n\t";

  if ($privaatsus == 3) $privaatsusHTML .= "<span>Privaatne</span> \n\t";
  else $privaatsusHTML .= '<span><a href="?privaatsus=3">Privaatne</a></span>' . "\n\t";
  

?>

<!DOCTYPE html>
<html lang="et">
  <head>
  <meta charset="utf-8">
  <title>Veebirakendused ja nende loomine 2020</title>
  <style>
    div.pilt {
      float: left;
      margin: 10px;
    }
    span {
      margin-right: 20px;
    }
  </style>
</head>
<body>
  <div>
    <?php echo $response[0]; ?>
</div>

  <div style="clear:both">
    <?php
      echo "<span>" . $eelmineLehekülg . "</span> \n\t";
      echo "<span>" . $järgmineLehekülg . "</span> \n\t<br><br><br> \n\t";
      echo $privaatsusHTML;
    ?>
    
    <br><br>
    <p><a href="home.php">Esileht</a></p>
    <p><a href="addNews.php">Lisa uudiseid</a></p>
    <p><a href="showNews.php">Vaata uudiseid</a></p>
    <p><a href="photoUpload.php">Lisa pilte</a></p>
    <p><a href="õpilogi.php">Täida õpilogi</a></p>
    <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
    
    <p>Logi <a href="?logout=1">välja</a></p>
  </div>
</body>
</html>