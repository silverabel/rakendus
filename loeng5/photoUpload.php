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

  $pildiÜleslaadimiseTulemus = null;
  if (isset($_POST["photoSubmit"])) {
    $pildiÜleslaadimiseTulemus = laePiltÜles();
    if ($pildiÜleslaadimiseTulemus == "1111") $pildiÜleslaadimiseTulemus = "Kõik läks hästi";
    else $pildiÜleslaadimiseTulemus = "Error: " . $pildiÜleslaadimiseTulemus;
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
  <h1>Fotode üleslaadimine</h1>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" enctype="multipart/form-data">
    <label>Vali pildifail: </label><br>
    <input type="file" name="fileToUpload" required><br>
    
    <label>Alt tekst: </label>
    <input type="text" name="altText"><br>
    
    <label>Privaatsus</label><br>

    <label for="priv1">Privaatne</label>
    <input id="priv1" type="radio" name="privacy" value="3" checked><br>
    <label for="priv2">Sisseloginud kasutajatele</label>
    <input id="priv2" type="radio" name="privacy" value="2"><br>
    <label for="priv3">Avalik</label>
    <input id="priv3" type="radio" name="privacy" value="1"><br>

    <input type="submit" name="photoSubmit" value="Lae üles">
    <span> <?php echo $pildiÜleslaadimiseTulemus; ?> </span>
  </form>
  <p><a href="home.php">Esileht</a></p>
  <p><a href="addNews.php">Lisa uudiseid</a></p>
  <p><a href="showNews.php">Vaata uudiseid</a></p>
  <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
  <p><a href="õpilogi.php">Täida õpilogi</a></p>
  <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>

  
  <p>Logi <a href="?logout=1">välja</a></p>

</body>
</html>