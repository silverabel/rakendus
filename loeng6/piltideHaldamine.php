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


  if (count($_POST) > 1) kustutaValitudPildid();


  $response = loePildihaldus();
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
    div.pilt {
      position: relative;
      float: left;
      margin-top: -9.5px;
    }
    div.pilt:first-of-type {
      margin-left: 0;
    }
    div.submit {
      position: relative;
      float: left;
      clear: both;
      margin-left: 0;
    }
  </style>
  <script src="javascript/piltideHaldamine.js" defer></script>
</head>
<body>
  <div style="border-right: 1px solid; padding-right: 10px; height: 80%">
    <p><a href="home.php">Esileht</a></p>
    <p><a href="addNews.php">Lisa uudiseid</a></p>
    <p><a href="showNews.php">Vaata uudiseid</a></p>
    <p><a href="photoUpload.php">Lisa pilte</a></p>
    <p>Halda pilte</p>
    <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
    <p><a href="õpilogi.php">Täida õpilogi</a></p>
    <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
    
    <p>Logi <a href="?logout=1">välja</a></p>
  </div>
  
  <div style="margin-left: 130px">
    <h1>Piltide haldamine</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <?php echo $response; ?>
      <div class="submit">
        <input name="submitUserData" type="submit" value="Kustuta valitud pildid">
      </div>
    </form>
  </div>


</body>
</html>