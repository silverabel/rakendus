<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");

  function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $uudisePealkiri = null;
  $uudiseSisu = null;
  $uudiseViga = null;
  if (isset($_POST["uudiseNupp"])) {
    if (isset($_POST["uudisePealkiri"]) and !empty(testInput(($_POST["uudisePealkiri"])))) $uudisePealkiri = testInput($_POST["uudisePealkiri"]);
    else $uudiseViga = "Uudise pealkiri on sisestamata. ";
    if (isset($_POST["uudiseSisu"]) and !empty(testInput(($_POST["uudiseSisu"])))) $uudiseSisu = testInput($_POST["uudiseSisu"]);
    else $uudiseViga .= "Uudise sisu on sisestamata.";
    
    //Saadame andmebaasi
    if (empty($uudiseViga)) {
      $response = salvestaUudis($uudisePealkiri, $uudiseSisu);
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

</style>
</head>
<body>
  <h1>Uudise lisamine</h1>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <label>Uudise pealkiri: </label><br>
    <input type="text" name="uudisePealkiri" placeholder="Uudise pealkiri" value="<?php echo($uudisePealkiri) ?>"><br>
    
    <label>Uudise sisu: </label><br>
    <textarea name="uudiseSisu" placeholder="Uudis" rows="10" cols="50"><?php echo($uudiseSisu) ?></textarea><br>

    <input type="submit" name="uudiseNupp" value="Salvesta">
    <span> <?php echo($uudiseViga); ?> </span>
  </form>

</body>
</html>