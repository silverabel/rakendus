<?php
class Pilt {
  private $üleslaetavaPildiMassiiv;
	private $ajutinePilt;
	private $maksimaalneLaius;
	private $maksimaalneKõrgus;
  public $failitüüp;
	public $failinimi;
	public $uusPilt;

  function __construct($üleslaetavaPildiMassiiv, $maksimaalneFailisuurus) {
		if ($üleslaetavaPildiMassiiv["size"] > $maksimaalneFailisuurus) throw new Exception("Valitud fail on liiga suur (max 1MB)");

		$this->üleslaetavaPildiMassiiv = $üleslaetavaPildiMassiiv;
		
		$lubatudFailitüübid = ["jpg", "png"];
		$this->failitüüp = $this->leiaFailitüüp();
		if (!in_array($this->failitüüp, $lubatudFailitüübid))	throw new Exception($this->failitüüp);



    //hiljem tuleks kõigepealt selgitada, kas on sobiv fail üleslaadimiseks ja ka imageFileType kindlaks teha klassi sees

    $this->ajutinePilt = $this->createImageFromFile($this->üleslaetavaPildiMassiiv["tmp_name"], $this->failitüüp);
  }


  function __destruct() {
    if (isset($this->ajutinePilt)) imagedestroy($this->ajutinePilt);
    if (isset($this->uusPilt)) imagedestroy($this->uusPilt);
  }

  private function createImageFromFile($imageFile, $fileType) {
    if ($fileType == "jpg") {
      $image = imagecreatefromjpeg($imageFile);
    }
    if ($fileType == "png") {
      $image = imagecreatefrompng($imageFile);
    }
    return $image;
  }

  public function resizePhoto($w, $h, $keepOrigProportion = true) {
		$imageW = imagesx($this->ajutinePilt);
		$imageH = imagesy($this->ajutinePilt);
		$newW = $w;
		$newH = $h;
		$cutX = 0;
		$cutY = 0;
		$cutSizeW = $imageW;
		$cutSizeH = $imageH;
		
		if($w == $h){
			if($imageW > $imageH){
				$cutSizeW = $imageH;
				$cutX = round(($imageW - $cutSizeW) / 2);
			} else {
				$cutSizeH = $imageW;
				$cutY = round(($imageH - $cutSizeH) / 2);
			}	
		} elseif($keepOrigProportion){//kui tuleb originaaproportsioone säilitada
			if($imageW / $w > $imageH / $h){
				$newH = round($imageH / ($imageW / $w));
			} else {
				$newW = round($imageW / ($imageH / $h));
			}
		} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
			if($imageW / $w < $imageH / $h){
				$cutSizeH = round($imageW / $w * $h);
				$cutY = round(($imageH - $cutSizeH) / 2);
			} else {
				$cutSizeW = round($imageH / $h * $w);
				$cutX = round(($imageW - $cutSizeW) / 2);
			}
		}
		
		//loome uue ajutise pildiobjekti
		$this->uusPilt = imagecreatetruecolor($newW, $newH);
		//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
	    imagesavealpha($this->uusPilt, true);
	    $transColor = imagecolorallocatealpha($this->uusPilt, 0, 0, 0, 127);
	    imagefill($this->uusPilt, 0, 0, $transColor);
		imagecopyresampled($this->uusPilt, $this->ajutinePilt, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);	
  }
  
  public function saveImgToFile($target) {
		$notice = 0;
		if($this->imageFileType == "jpg"){
			if(imagejpeg($this->uusPilt, $target, 90)) $notice = 1;
		}
		if($this->imageFileType == "png"){
			if(imagepng($this->uusPilt, $target, 6))	$notice = 1;
		}
		return $notice;
	}

	private function leiaFailitüüp() {
		$check = getimagesize($this->üleslaetavaPildiMassiiv["tmp_name"]);
		if (!$check) return "Valitud fail pole pilt";

		if ($check["mime"] == "image/jpeg") return "jpg";
		if ($check["mime"] == "image/png") return "png";
		return "Fail peab olema jpeg või png";
	}

	public function genereeriFailinimi($prefix) {
		$timestamp = microtime(1) * 10000;
		$this->failinimi = $prefix . $timestamp . "." . $this->failitüüp;
	}

	public function salvestaOriginaalFail($originalPhotoDir) {
		$target = $originalPhotoDir . $this->failinimi;
		return move_uploaded_file($this->üleslaetavaPildiMassiiv["tmp_name"], $target);
	}
}