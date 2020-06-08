<?php
require("../../../../configuration.php");
require("funktsioonid.php");

$myName = "\t<h1>Silver Abel</h1> \n";

$praeguneAeg = date("d.m.Y H:i:s");
$aegHTML = "\t<p>Praegu on <strong>" . date("d.m.Y H:i:s") . "</strong></p> \n";
$praeguneTund = date("H");
$päevaOsa = "hägune aeg";
$kellaajaleVastavStiil = "body {\n\tbackground-color: #79554866;\n  }\n";
if ($praeguneTund < 10) {
  $päevaOsa = "hommik";
  $kellaajaleVastavStiil = "body {\n\tbackground-color: #0000ff91;\n\tcolor: white;\n  }\n";
}
else if ($praeguneTund < 18) {
  $päevaOsa = "aeg aktiivselt tegutseda";
  $kellaajaleVastavStiil = "body {\n\tbackground-color: green;\n\tcolor: yellow;\n  }\n";
}
$päevaOsaHTML = "\t<p>On " . $päevaOsa . "</p> \n";

//info semestri kulgemise kohta
$semestriAlgus = new DateTime("2020-01-27");
$semestriLõpp = new DateTime("2020-06-22");
$semestriPikkus = $semestriAlgus->diff($semestriLõpp)->format("%r%a");
//var_dump($semestriPikkus)
$tänaneKuupäev = new DateTime("now");
$semestriAlgusestTänaseni = $semestriAlgus->diff($tänaneKuupäev)->format("%r%a");

if ($semestriAlgusestTänaseni < 0 ) $semestriProgressHTML = "\t<p>Semester pole veel alanud</p>\n";
else if ($semestriAlgusestTänaseni > $semestriPikkus) $semestriProgressHTML = "\t<p>Semester on läbi</p>\n";
else {
  $semestriProgressHTML = "\t" . '<p>Semester kulgeb: <progress min="0" max="' . $semestriPikkus;
  $semestriProgressHTML .= '" value="' . $semestriAlgusestTänaseni . '"></progress></p>' . "\n";
}

//loen etteantud kataloogist pildifailid
$pildiKataloog = "../../pildid/";
$lubatudFotoTüübid = ["image/jpeg", "image/png"];
$fotoList = [];
$kõikFailid = array_slice(scandir($pildiKataloog), 2);
foreach ($kõikFailid as $fail) {
  $fileInfo = getimagesize($pildiKataloog . $fail);
  if (in_array($fileInfo["mime"], $lubatudFotoTüübid)) {
    array_push($fotoList, $fail);
  }
}
$fotoNumbrid = array();
while (count($fotoNumbrid) < 3) {
  $fotoNumber = mt_rand(0, count($fotoList) - 1);
  if (!in_array($fotoNumber, $fotoNumbrid)) array_push($fotoNumbrid, $fotoNumber);
}
$randomPildidHTML = "";
foreach ($fotoNumbrid as $fotoNumber) {
  $randomPildidHTML .= "\t" . '<img src="' . $pildiKataloog . $fotoList[$fotoNumber] . '" alt="pilt">' . "\n";
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
<meta charset="utf-8">
<title>Veebirakendused ja nende loomine 2020</title>
<style>
  progress {
    width: 25%;
  }
  img {
    height: 200px;
  }
  <?php
    echo $kellaajaleVastavStiil;
  ?>
  .uudised h2 {
  margin-bottom: 0;
  }
</style>
</head>
<body>
<?php 
  echo $myName;
  echo $aegHTML; 
  echo $päevaOsaHTML;
  echo $semestriProgressHTML;
  echo $randomPildidHTML;
  echo ('<div class="uudised">' . "\n");
  echo ("<h1>Uudised</h1> \n");
  echo loeUudised(1);
  echo("</div> \n");
?>
</body>
</html>