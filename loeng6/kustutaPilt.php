<?php
require("../../../../configuration.php");
require("classes/Session.class.php");

SessionManager::sessionStart("vr20", 0, "/~silver.abel/", "tigu.hk.tlu.ee");

$id = $_REQUEST["photoId"];

$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
$stmt = $conn->prepare("UPDATE vr20_photos SET deleted = now() WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();
