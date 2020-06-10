<?php
  function salvestaUudis($userid, $uudisePealkiri, $uudiseSisu, $uudisePilt) {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $photoidFromDB = null;

    if (isset($uudisePilt)) {
      try {
        $photoUp = new Pilt($uudisePilt, 1000000);
      }
      catch (Exception $exception) {
        return $exception->getMessage();
      } 
    
      $photoUp->genereeriFailinimi("vr_");
    
      $maxWidth = 600;
      $maxHeight = 400;
      $normalPhotoDir = "../../uploadNormalPhoto/";
      $photoUp->resizePhoto($maxWidth, $maxHeight);
      saveImgToFile($photoUp->uusPilt, $normalPhotoDir . $photoUp->failinimi, $photoUp->failitüüp);
    
      $thumbnailDir = "../../thumbnail/";
      $photoUp->resizePhoto(100, 100, false);
      saveImgToFile($photoUp->uusPilt, $thumbnailDir . $photoUp->failinimi, $photoUp->failitüüp);
    
      $originalPhotoDir = "../../uploadOriginalPhoto/";
      $photoUp->salvestaOriginaalFail($originalPhotoDir);
      

      //valmistame ette SQL päringu
      $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, privacy) VALUES (?, ?, ?)");
      echo $conn->error;
  
      $privaatsus = 2;
      //seome andmed päringuga
      // i - integer, s - string, d - decimal
      $stmt->bind_param("isi", $_SESSION["userid"], $photoUp->failinimi, $privaatsus);
  
      $stmt->execute();
      echo($stmt->error);
  
      //sulgen päringu
      $stmt->close();
    }

    $response = null;
    //valmistame ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_uudised (userid, title, content, photoid) VALUES (?, ?, ?, (SELECT LAST_INSERT_ID()))");
    echo $conn->error;

    //seome andmed päringuga
    // i - integer, s - string, d - decimal
    $stmt->bind_param("iss", $userid, $uudisePealkiri, $uudiseSisu);

    if ($stmt->execute()) $response = 1; else $response = 0; echo $stmt->error;

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return $response;
  }

  function loeUudised($väljastatavateUudisteArv) {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $conn->prepare("SELECT vr20_users.firstname, vr20_users.lastname, vr20_uudised.title, vr20_uudised.content, vr20_uudised.created, vr20_photos.filename, vr20_photos.deleted
    FROM vr20_uudised LEFT OUTER JOIN vr20_photos ON vr20_uudised.photoid = vr20_photos.id LEFT OUTER JOIN vr20_users ON vr20_uudised.userid = vr20_users.id
    WHERE vr20_uudised.deleted IS NULL ORDER BY vr20_uudised.id DESC LIMIT ?");
    echo $conn->error;

    $stmt->bind_param("i", $väljastatavateUudisteArv);
    $stmt->bind_result($firstnameFromDB, $lastnameFromDB, $titleFromDB, $contentFromDB, $createdFromDB, $photoFilenameFromDB, $photoDeletedFromDB);
    $stmt->execute();

    
    $response = null;
    while ($stmt->fetch()) {
      $loomiseAeg = new DateTime($createdFromDB);
      $response .= '<div class="uudis"><h2>' . $titleFromDB . "</h2> \n";
      $response .= "<span>" . $loomiseAeg->format("d-m-Y") . "</span> \n";
      $response .= "<span>" . $firstnameFromDB . " " . $lastnameFromDB . "</span> \n";
      $response .= '<div class="content">' . $contentFromDB . "</div> \n";
      if (isset($photoFilenameFromDB) && $photoDeletedFromDB == null) $response .= '<img src="../../uploadNormalPhoto/' . $photoFilenameFromDB . '">' . "\n";
      $response .= "</div>\n";
    }

    if (!$response) $response = "<p>Uudise puuduvad</p>";

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return $response;
  }

  function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
  }
  

  function salvestaÕpilogisse() {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //valmistame ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_studylog (userid, course, activity, time) VALUES (?, ?, ?, ?)");
    echo $conn->error;


    //seome andmed päringuga
    // i - integer, s - string, d - decimal
    $stmt->bind_param("iiid", $_SESSION["userid"], $_POST["õppeaine"], $_POST["tegevus"], $_POST["tegevuseAegTundides"]);

    $response = null;

    if ($stmt->execute()) $response = "Kõik läks hästi"; else $response = "Midagi läks valesti"; echo $stmt->error;

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return $response;
  }

  function loeÕpilogi() {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vr20_users");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB);
    $stmt->execute();

    $kasutajadMassiiv = [];
    while ($stmt->fetch()) {
      $kasutajadMassiiv[$idFromDB] = $firstnameFromDB . " " . $lastnameFromDB;
    }

    $stmt->close();

    $stmt = $conn->prepare("SELECT userid, course, activity, time, day FROM vr20_studylog");
    echo $conn->error;

    //$stmt->bind_param("i", $väljastatavateUudisteArv);
    $stmt->bind_result($useridFromDB, $courseFromDB, $activityFromDB, $timeFromDB, $dayFromDB);
    $stmt->execute();

    $response = null;
    while ($stmt->fetch()) {
      $lisamiseAeg = new DateTime($dayFromDB);
      $õppeaine = leiaÕppeaine($courseFromDB);
      $tegevus = leiaTegevus($activityFromDB);
      if (isset($kasutajadMassiiv[$useridFromDB])) $userid = $kasutajadMassiiv[$useridFromDB];
      else $userid = "Teadmata kasutaja";

      $response .= "<tr><td>" . $userid . "</td> \n";
      $response .= "<td>" . $õppeaine . "</td> \n";
      $response .= "<td>" . $tegevus . "</td> \n";
      $response .= "<td>" . $timeFromDB . "</td> \n";
      $response .= "<td>" . $lisamiseAeg->format("d-m-Y") . "</td></tr> \n";
    }

    if (!$response) $response = "<p>Logi tühi</p>";

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return $response;
  }

  function leiaÕppeaine($õppeaineNumber) {
    if ($õppeaineNumber == 1) return "Veebirakendused ja nende loomine";
    if ($õppeaineNumber == 2) return "Programmeerimine";
    if ($õppeaineNumber == 3) return "Psühholoogia";
    if ($õppeaineNumber == 4) return "Disain";
    if ($õppeaineNumber == 5) return "Andmebaasid";
    return "Viga";
  }

  function leiaTegevus($tegevuseNumber) {
    if ($tegevuseNumber == 1) return "Iseseisev materjali omandamine";
    if ($tegevuseNumber == 2) return "Koduste ülesannete lahendamine";
    if ($tegevuseNumber == 3) return "Kordamine";
    if ($tegevuseNumber == 4) return "Rühmatöö";
    return "Viga";
  }



  function loePildigalerii($lehenumber, $privaatsus) {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $pilteVaheleJätta = 3 * $lehenumber - 3;
    $pilteNäidata = 4;

    if ($privaatsus == 3) {
      $stmt = $conn->prepare("SELECT vr20_users.firstname, vr20_users.lastname, vr20_photos.filename, vr20_photos.id, vr20_photos.alttext, AVG(vr20_photoratings.rating) 
      FROM vr20_photos INNER JOIN vr20_users ON vr20_users.id = vr20_photos.userid LEFT OUTER JOIN vr20_photoratings ON vr20_photos.id = vr20_photoratings.photoid
      WHERE vr20_photos.privacy = ? AND vr20_photos.userid = ? AND deleted IS NULL GROUP BY vr20_photos.id LIMIT ?, ?");
      $stmt->bind_param("iiii", $privaatsus, $_SESSION["userid"], $pilteVaheleJätta, $pilteNäidata);
    }
    else {
      $stmt = $conn->prepare("SELECT vr20_users.firstname, vr20_users.lastname, vr20_photos.filename, vr20_photos.id, vr20_photos.alttext, AVG(vr20_photoratings.rating) 
      FROM vr20_photos INNER JOIN vr20_users ON vr20_users.id = vr20_photos.userid LEFT OUTER JOIN vr20_photoratings ON vr20_photos.id = vr20_photoratings.photoid
      WHERE vr20_photos.privacy = ? AND deleted IS NULL GROUP BY vr20_photos.id LIMIT ?, ?");
      $stmt->bind_param("iii", $privaatsus, $pilteVaheleJätta, $pilteNäidata);
    }       
    echo $conn->error;

    $stmt->bind_result($userFirstnameFromDB, $userLastnameFromDB, $filenameFromDB, $photoidFromDB, $photoAlttextFromDB, $photoRatingFromDB);
    $stmt->execute();

    $piltideArv = 0;
    $response = null;
    while ($stmt->fetch()) {
      $piltideArv += 1;
      if ($piltideArv == 4) break;

      $thumbnailDir = "../../thumbnail/";
      $normalPhotoDir = "../../uploadNormalPhoto/";
      $response .= '<div class="pilt">' . "\n\t\t";
      $response .= '<img data-id="' . $photoidFromDB . '" data-fn="' . $filenameFromDB . '" src="' . $thumbnailDir . $filenameFromDB . '">'  . "\n\t\t" . '<br>';
      $response .= $userFirstnameFromDB . " " . $userLastnameFromDB . "\n\t<br>Hinne:" . round($photoRatingFromDB, 2) . "\n\t" . '</div>' . "\n\t";
    }

    if (!$response) $response = '<div class="pilt" style="height: 140.8px">Pilte pole</div>';

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return [$response, $piltideArv];
  }

  function loePildihaldus() {
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $privaatsus = 3;

    $stmt = $conn->prepare("SELECT vr20_photos.filename, vr20_photos.id, vr20_photos.alttext, vr20_photos.privacy, AVG(vr20_photoratings.rating) 
    FROM vr20_photos INNER JOIN vr20_users ON vr20_users.id = vr20_photos.userid LEFT OUTER JOIN vr20_photoratings ON vr20_photos.id = vr20_photoratings.photoid
    WHERE vr20_photos.privacy <= ? AND vr20_photos.userid = ? AND deleted IS NULL GROUP BY vr20_photos.id");
    $stmt->bind_param("ii", $privaatsus, $_SESSION["userid"]);

    echo $conn->error;

    $stmt->bind_result($filenameFromDB, $photoidFromDB, $photoAlttextFromDB, $privacyFromDB, $photoRatingFromDB);
    $stmt->execute();

    $response = null;
    while ($stmt->fetch()) {
      if ($privacyFromDB == 3) $privaatsus = "Privaatne";
      else if ($privacyFromDB == 2) $privaatsus = "Sisseloginud kasutajatele";
      else $privaatsus = "Avalik";

      $thumbnailDir = "../../thumbnail/";
      $normalPhotoDir = "../../uploadNormalPhoto/";
      $response .= '<div class="pilt">' . "\n\t\t" . '<input type="checkbox" name="' . $photoidFromDB . '"><br>' . "\n\t";
      $response .= '<img data-id="' . $photoidFromDB . '" data-fn="' . $filenameFromDB . '" src="' . $thumbnailDir . $filenameFromDB . '">'  . "\n\t\t<br>";
      $response .= "<span>" . $privaatsus . "</span>\n\t<br>";
      $response .= "Hinne:" . round($photoRatingFromDB, 2) . "\n\t" . '<br><button type="button" data-id=' . $photoidFromDB .'" onclick="kustutaPilt(this)">Kustuta</button>' . '</div>' . "\n\t";
    }

    if (!$response) $response = '<div class="pilt" style="height: 140.8px; margin-top: 10px">Pilte pole</div>';

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return $response;
  }

function kustutaValitudPildid() {
  $pildiIDMassiiv = [];
  foreach($_POST as $key => $value) {
    if ($key != "submitUserData") {
      array_push($pildiIDMassiiv, $key);
    }
  }
  $küsimärkideString = str_repeat("?, ", count($pildiIDMassiiv) - 1) . "?";
  $parameetriteString = str_repeat("s", count($pildiIDMassiiv));
  $sql = "UPDATE vr20_photos SET deleted = now() WHERE id IN ($küsimärkideString)";
  
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($parameetriteString, ...$pildiIDMassiiv);
  $stmt->execute();
  $stmt->close();

  $conn->close();
}