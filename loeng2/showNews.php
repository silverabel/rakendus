<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");

  $uudisedHTML = loeUudised(5);
  


?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Veebirakendused ja nende loomine 2020</title>
  <style>
    .uudised h2 {
      margin-bottom: 0;
    }
  </style>
</head>
<body>
  <h1>Uudised</h1>
  <div class="uudised">
    <?php echo $uudisedHTML ?>
  </div>

</body>
</html>