<?php
  require("../../../../configuration.php");
  require("funktsioonid.php");


  //pildi üleslaadimine

  $originalPhotoDir = "../../uploadOriginalPhoto/";
  $error = null;
  $imageFileType = null;
  $fileUploadSizeLimit = 1000000;

  do {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    if (!$check) {
      $error = "Valitud fail ei ole pilt!";
      break;
    }

    


  } while (0);
  

  /*
  if (isset($_POST["photoSubmit"])) {
    //kas üldse on pilt
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
      //failitüübi väljaselgitamine ja sobivuse kontroll
      if ($check["mime"] == "image/jpeg") {
        $imageFileType = "jpg";
      }
      elseif ($check["mime"] == "image/png") {
        $imageFileType = "png";
      }
      else {
        $error = "Ainult jpg või png!";
      }

    }
    else {
      $error = "Valitud fail ei ole pilt!";
    }

    //ega pole liiga suur
    if ($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit) $error .= " Valitud fail on liiga suur (1MB)";

    $originalTarget = $originalPhotoDir . $_FILES["fileToUpload"]["name"];

    //äkki on fail olemas
    if (file_exists($originalTarget)) $error .= " Fail on juba olemas";

    if ($error == null) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)) {
        $error = "Originaalpilt laeti üles";
      }
      else {
        $error = "Pildi üleslaadimisel tekkis viga";
      }
    }
    
  }
  */
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
    <input type="file" name="fileToUpload"><br>
    
    <input type="submit" name="photoSubmit" value="Lae üles">
    <span> <?php echo $error ?> </span>
  </form>

</body>
</html>