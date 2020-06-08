<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");
  require("fnc_photoUpload.php");
  require("classes/Photo.class.php");
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


  function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $uudisePealkiri = null;
  $uudiseSisu = null;
  $uudisePilt = null;
  $uudiseViga = null;
  if (isset($_POST["uudiseNupp"])) {
    if (isset($_POST["uudisePealkiri"]) and !empty(testInput(($_POST["uudisePealkiri"])))) $uudisePealkiri = testInput($_POST["uudisePealkiri"]);
    else $uudiseViga = "Uudise pealkiri on sisestamata. ";
    if (isset($_POST["uudiseSisu"]) and !empty(testInput(($_POST["uudiseSisu"])))) $uudiseSisu = testInput($_POST["uudiseSisu"]);
    else $uudiseViga .= "Uudise sisu on sisestamata.";
    if (!empty($_FILES["fileToUpload"]["name"])) $uudisePilt = $_FILES["fileToUpload"];
    
    //Saadame andmebaasi
    if (empty($uudiseViga)) {
      $response = salvestaUudis($_SESSION["userid"], $uudisePealkiri, $uudiseSisu, $uudisePilt);
      if ($response) $uudiseViga = "Uudis on salvestatud"; else $uudiseViga = "Uudise salvestamisel tekkis viga";
    }
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
    <p><a href="home.php">Esileht</a></p>
    <p>Lisa uudiseid</p>
    <p><a href="showNews.php">Vaata uudiseid</a></p>
    <p><a href="photoUpload.php">Lisa pilte</a></p>
    <p><a href="piltideHaldamine.php">Halda pilte</a></p>
    <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
    <p><a href="õpilogi.php">Täida õpilogi</a></p>
    <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
    
    <p>Logi <a href="?logout=1">välja</a></p>
  </div>
  
  <div style="margin-left: 130px">
    <h1>Uudise lisamine</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" enctype="multipart/form-data">
      <label>Uudise pealkiri: </label><br>
      <input type="text" name="uudisePealkiri" placeholder="Uudise pealkiri" value="<?php echo($uudisePealkiri) ?>"><br>
      
      <label>Uudise sisu: </label><br>
      <textarea name="uudiseSisu" placeholder="Uudis" rows="10" cols="50"><?php echo($uudiseSisu) ?></textarea><br>

      <label>Lisa pilt: </label><br>
      <input type="file" name="fileToUpload"><br><br><br>

      <input type="submit" name="uudiseNupp" value="Salvesta">
      <span> <?php echo($uudiseViga); ?> </span>
    </form>
  </div>
</body>
</html>