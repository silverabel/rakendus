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
    div.pilt:first-of-type {
      margin-left: 0;
    }
    div.lingid {
      float: left;
      clear: both;
    }
    span {
      margin-right: 20px;
    }
    img:hover {
      cursor: pointer;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="style/modal.css">
  <script src="javascript/modal.js" defer></script>
</head>
<body>
<div id="modalArea" class="modalArea">
	<!--Sulgemisnupp-->
	<span id="modalClose" class="modalClose">&times;</span>
	<!--pildikoht-->
	<div class="modalHorizontal" id="modalHorizontal">
		<div class="modalVertical">
		<p id="modalCaption"></p>
			<img src="../../thumbnail/vr_15892060599324.jpg" id="modalImg" class="modalImg" alt="galeriipilt">

      <div id="rating" class="modalRating">
				<label><input id="rate1" name="rating" type="radio" value="1">1</label>
				<label><input id="rate2" name="rating" type="radio" value="2">2</label>
				<label><input id="rate3" name="rating" type="radio" value="3">3</label>
				<label><input id="rate4" name="rating" type="radio" value="4">4</label>
				<label><input id="rate5" name="rating" type="radio" value="5">5</label>
				<button id="storeRating">Salvesta hinnang!</button>
				<br>
				<p id="avgRating"></p>
			</div>

		</div>
	</div>
</div>  

<div style="border-right: 1px solid; padding-right: 10px; height: 80%; position: absolute; margin: 10px">
  <p><a href="home.php">Esileht</a></p>
  <p><a href="addNews.php">Lisa uudiseid</a></p>
  <p><a href="showNews.php">Vaata uudiseid</a></p>
  <p><a href="photoUpload.php">Lisa pilte</a></p>
  <p><a href="piltideHaldamine.php">Halda pilte</a></p>
  <p>Vaata galeriid</p>
  <p><a href="õpilogi.php">Täida õpilogi</a></p>
  <p><a href="õpilogiTabel.php">Vaata õpilogi</a></p>
  
  <p>Logi <a href="?logout=1">välja</a></p>
</div>

<div class="gallery" id="gallery" style="margin: 10px; margin-left: 130px; position: absolute;">
  <h1>Pildigalerii</h1>
  <?php echo $response[0]; ?>


  <div class="lingid">
    <?php
    echo "<span>" . $eelmineLehekülg . "</span> \n\t";
    echo "<span>" . $järgmineLehekülg . "</span> \n\t<br><br><br> \n\t";
    echo $privaatsusHTML;
    ?>
  </div>
</div>

</body>
</html>