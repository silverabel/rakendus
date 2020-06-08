<?php
  function salvestaUudis($uudisePealkiri, $uudiseSisu) {
    $response = null;
    //loome andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //valmistame ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_uudised (userid, title, content) VALUES (?, ?, ?)");
    echo $conn->error;

    //seome andmed päringuga
    $userid = 1;
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