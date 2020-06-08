<?php
  function salvestaUudis($userid, $uudisePealkiri, $uudiseSisu) {
    $response = null;
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //valmistame ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_uudised (userid, title, content) VALUES (?, ?, ?)");
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

    $stmt = $conn->prepare("SELECT title, content, created FROM vr20_uudised WHERE deleted IS NULL ORDER BY id ASC LIMIT ?");
    echo $conn->error;

    $stmt->bind_param("i", $väljastatavateUudisteArv);
    $stmt->bind_result($titleFromDB, $contentFromDB, $createdFromDB);
    $stmt->execute();

    
    $response = null;
    while ($stmt->fetch()) {
      $loomiseAeg = new DateTime($createdFromDB);
      $response .= "<h2>" . $titleFromDB . "</h2> \n";
      $response .= "<span>" . $loomiseAeg->format("d-m-Y") . "</span> \n";
      $response .= "<p>" . $contentFromDB . "</p> \n";
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

    $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vr20_users");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB);
    $stmt->execute();

    $kasutajadMassiiv = [];
    while ($stmt->fetch()) {
      $kasutajadMassiiv[$idFromDB] = $firstnameFromDB . " " . $lastnameFromDB;
    }

    $stmt->close();


    $pilteVaheleJätta = 3 * $lehenumber - 3;
    $pilteNäidata = 4;

    if ($privaatsus == 3) {
      $stmt = $conn->prepare("SELECT userid, filename FROM vr20_photos WHERE privacy = ? AND userid = ? LIMIT ?, ?");
      $stmt->bind_param("iiii", $privaatsus, $_SESSION["userid"], $pilteVaheleJätta, $pilteNäidata);
    }
    else {
      $stmt = $conn->prepare("SELECT userid, filename FROM vr20_photos WHERE privacy = ? LIMIT ?, ?");
      $stmt->bind_param("iii", $privaatsus, $pilteVaheleJätta, $pilteNäidata);
    }       
    echo $conn->error;


    $stmt->bind_result($useridFromDB, $filenameFromDB);
    $stmt->execute();

    $piltideArv = 0;
    $response = null;
    while ($stmt->fetch()) {
      $piltideArv += 1;
      if ($piltideArv == 4) break;

      if (isset($kasutajadMassiiv[$useridFromDB])) $userid = $kasutajadMassiiv[$useridFromDB];
      else $userid = "Teadmata kasutaja";

      $thumbnailDir = "../../thumbnail/";
      $normalPhotoDir = "../../uploadNormalPhoto/";
      $response .= '<div class="pilt">' . "\n\t\t" . '<a href="' . $normalPhotoDir . $filenameFromDB . '">' . "\n\t\t\t";
      $response .= '<img src="' . $thumbnailDir . $filenameFromDB . '">' . "\n\t\t" . '</a>' . "\n\t\t" . '<br>';
      $response .= $userid . "\n\t" . '</div>' . "\n\t";
    }

    if (!$response) $response = "Pilte pole";

    //sulgen päringu ja ühenduse
    $stmt->close();
    $conn->close();
    return [$response, $piltideArv];
  }