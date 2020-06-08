<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");
  

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
    <select name="õppeaine">
      <option value="" selected disabled>Õppeaine</option>
      <option value="1">Veebirakendused ja nende loomine</option> 
      <option value="2">Programmeerimine</option> 
      <option value="3">Psühholoogia</option> 
      <option value="4">Disain</option> 
      <option value="5">Andmebaasid</option> 
    </select> 
    <select name="tegevus">
      <option value="" selected disabled>Tegevus</option>
      <option value="1">Iseseisev materjali omandamine</option> 
      <option value="2">Koduste ülesannete lahendamine</option> 
      <option value="3">Kordamine</option> 
      <option value="4">Rühmatöö</option>  
    </select> 
    <input type="number" min=".25" max="24" step=".25" name="tegevuseAegTundides">
    Mitu tundi <br><br>
    <input type="submit" name="uudiseNupp" value="Salvesta">
  </form>
</body>
</html>