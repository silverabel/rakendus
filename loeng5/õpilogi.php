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

  $response = null;

  if ($_POST) $response = salvestaÕpilogisse();

  

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
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <select name="õppeaine" required>
      <option value="" selected disabled>Õppeaine</option>
      <option value="1">Veebirakendused ja nende loomine</option> 
      <option value="2">Programmeerimine</option> 
      <option value="3">Psühholoogia</option> 
      <option value="4">Disain</option> 
      <option value="5">Andmebaasid</option> 
    </select> 
    <select name="tegevus" required>
      <option value="" selected disabled>Tegevus</option>
      <option value="1">Iseseisev materjali omandamine</option> 
      <option value="2">Koduste ülesannete lahendamine</option> 
      <option value="3">Kordamine</option> 
      <option value="4">Rühmatöö</option>  
    </select> 
    <input type="number" min=".25" max="24" step=".25" name="tegevuseAegTundides" required>
    Mitu tundi <br><br>
    <input type="submit" name="uudiseNupp" value="Salvesta">
    <?php echo $response; ?>
  </form>
  <p><a href="home.php">Esileht</a></p>
  <p><a href="addNews.php">Lisa uudiseid</a></p>
  <p><a href="showNews.php">Vaata uudiseid</a></p>
  <p><a href="photoUpload.php">Lisa pilte</a></p>
  <p><a href="pildiGalerii.php">Vaata galeriid</a></p>
  <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
  
  
  <p>Logi <a href="?logout=1">välja</a></p>
</body>
</html>