<?php


function signUp($name, $surname, $email, $gender, $birthDate, $password) {
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

  $stmt = $conn->prepare("SELECT id FROM vr20_users WHERE email = ?");
  echo $conn->error;

  $stmt->bind_param("s", $email);
  $stmt->execute();

  if ($stmt->fetch()) {
    $stmt->close();
    $conn->close();
    return "Sellise meiliga kasutaja on juba olemas";
  }

  $stmt->close();



  $stmt = $conn->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
  echo $conn->error;

  //krüpteerin parooli
  $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
  $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);

  $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);

  $notice = null;

  if ($stmt->execute()) {
    $notice = "ok";
  }
  else {
    $notice = $stmt->error;
  }

  $stmt->close();
  $conn->close();

  return $notice;
}


function signIn($email, $password) {
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

  $stmt = $conn->prepare("SELECT password FROM vr20_users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->bind_result($passwordFromDB);
  echo $conn->error;

  $stmt->execute();

  if (!$stmt->fetch()) {
    $stmt->close();
    $conn->close();
    return "Sellist kasutajat pole olemas";
  }

  if (!password_verify($password, $passwordFromDB)) {
    $stmt->close();
    $conn->close();
    return "Vale parool";
  }

  $stmt->close();

  $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vr20_users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB);
  echo $conn->error;

  $stmt->execute();

  $stmt->fetch();

  $_SESSION["userid"] = $idFromDB;
  $_SESSION["userFirstname"] = $firstnameFromDB;
  $_SESSION["userLastname"] = $lastnameFromDB;


  $stmt->close();
  $conn->close();
  header("Location: home.php");
  exit();
}