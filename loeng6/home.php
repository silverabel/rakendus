  <?php
  require("../../../../configuration.php");
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

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Veebirakendused ja nende loomine 2020</title>
  <style>
    div {
      margin: 10px;
      position: absolute;
    }
  </style>
</head>
<body>
  <div style="border-right: 1px solid; padding-right: 10px; height: 80%">
    <p>Esileht</p>
    <p><a href="addNews.php">Lisa uudiseid</a></p>
    <p><a href="showNews.php">Vaata uudiseid</a></p>
    <p><a href="photoUpload.php">Lisa pilte</a></p>
    <p><a href="piltideHaldamine.php">Halda pilte</a></p>
    <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
    <p><a href="õpilogi.php">Täida õpilogi</a></p>
    <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
    
    <p>Logi <a href="?logout=1">välja</a></p>
  </div>
  
  <div style="margin-left: 130px">
    <h1>Esileht</h1>
    <p>
      Tere <?php echo $_SESSION["userFirstname"] . " " .  $_SESSION["userLastname"]; ?>
    </p>
  </div>
  

</body>
</html>