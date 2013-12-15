<?php

require_once("php/page.php");
require_once("php/general.php");

class OperadPage extends Page {
  public function __construct($database, $key) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name, notation FROM operads WHERE key = :key");
    $sql->bindParam(":key", $key);

    if ($sql->execute())
      $this->operad = $sql->fetch();
  }
  
  public function getMain() {
    $value = "";

    return $value;
  }
  public function getSidebar() {
    $value = "";


    return $value;
  }
  public function getTitle() {
    return " &mdash; Operad: " . $this->operad["name"];
  }
}

?>

