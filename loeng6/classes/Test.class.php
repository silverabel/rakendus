<?php
class Test {
  //properties ehk muutujad
  private $secretNum = 3;
  public $number = 9;

  function __construct() {
    echo "Laeti klass";
    echo "Salanumber on" . $this->secretNum;
    echo "Avanumber on" . $this->number;
  }

  function __destruct() {
    echo "Klass lõpetab";
  }
}