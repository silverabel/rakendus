<?php
function saveImgToFile($myNewImage, $target, $imageFileType){
		$notice = 0;
		if($imageFileType == "jpg"){
			if(imagejpeg($myNewImage, $target, 90)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		if($imageFileType == "png"){
			if(imagepng($myNewImage, $target, 6)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		return $notice;
	}

function laePiltÜles() {
	try {
		$photoUp = new Pilt($_FILES["fileToUpload"], 1000000);
	}
	catch (Exception $exception) {
		return $exception->getMessage();
	} 


	$photoUp->genereeriFailinimi("vr_");

	$maxWidth = 600;
	$maxHeight = 400;
	$normalPhotoDir = "../../uploadNormalPhoto/";
	$photoUp->resizePhoto($maxWidth, $maxHeight);
	$result = saveImgToFile($photoUp->uusPilt, $normalPhotoDir . $photoUp->failinimi, $photoUp->failitüüp);

	$thumbnailDir = "../../thumbnail/";
	$photoUp->resizePhoto(100, 100, false);
	$result .= saveImgToFile($photoUp->uusPilt, $thumbnailDir . $photoUp->failinimi, $photoUp->failitüüp);

	$originalPhotoDir = "../../uploadOriginalPhoto/";
	if ($photoUp->salvestaOriginaalFail($originalPhotoDir)) $result .= 1;
	else return "Originaalpildi üleslaadimisel tekkis viga.";

	$result .= salvestaPildiinfoAndmebaasi($_SESSION["userid"], $photoUp->failinimi, $_POST["altText"], $_POST["privacy"]);
	return $result;
}

function salvestaPildiinfoAndmebaasi($kasutajaID, $failiNimi, $alternatiivneTekst, $privaatsus) {
	//loome andmebaasi ühenduse
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

	//valmistame ette SQL päringu
	$stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
	echo $conn->error;

	//seome andmed päringuga
	// i - integer, s - string, d - decimal
	$stmt->bind_param("issi", $kasutajaID, $failiNimi, $alternatiivneTekst, $privaatsus);

	$response = null;
	if ($stmt->execute()) $response = 1; else $response = 0; echo $stmt->error;

	//sulgen päringu ja ühenduse
	$stmt->close();
	$conn->close();
	return $response;
}