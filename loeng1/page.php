<?php
  $myName = "\t<h1>Silver Abel</h1> \n";
  $praeguneAeg = date("d.m.Y H:i:s");
  $aegHTML = "\t<p>Praegu on <strong>" . date("d.m.Y H:i:s") . "</strong></p> \n";
  $praeguneTund = date("H");
  $päevaOsa = "hägune aeg";
  if ($praeguneTund < 10) {
    $päevaOsa = "hommik";
  }
  if ($praeguneTund >= 10 and $praeguneTund < 18) {
    $päevaOsa = "aeg aktiivselt tegutseda";
  }
  $päevaOsaHTML = "\t<p>On " . $päevaOsa . "</p> \n";

  //info semestri kulgemise kohta
  $semestriAlgus = new DateTime("2020-01-27");
  $semestriLõpp = new DateTime("2020-06-22");
  $semestriPikkus = $semestriAlgus->diff($semestriLõpp)->format("%r%a");
  //var_dump($semestriPikkus)
  $tänaneKuupäev = new DateTime("now");
  $semestriAlgusestTänaseni = $semestriAlgus->diff($tänaneKuupäev)->format("%r%a");
  $semestriProgressHTML = "\t" . '<p>Semester kulgeb: <progress min="0" max="' . $semestriPikkus;
  $semestriProgressHTML .= '" value="' . $semestriAlgusestTänaseni . '"></progress></p>' . "\n";

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
  $fotodeArv = count($fotoList);
  $fotoNumber = mt_rand(0, $fotodeArv - 1);
  $randomPiltHTML = "\t" . '<img src="' . $pildiKataloog . $fotoList[$fotoNumber] . '" alt="pilt">' . "\n";

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
  </style>
</head>
<body>
  <?php 
    echo $myName;
    echo $aegHTML; 
    echo $päevaOsaHTML;
    echo $semestriProgressHTML;
    echo $randomPiltHTML;
  ?>
</body>
</html>