<?php

require_once("php/page.php");
require_once("php/general.php");

class OperadsPage extends Page {
  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name FROM operads");

    if ($sql->execute()) {
      $this->operads = $sql->fetchAll();
    }
  }
  
  public function getMain() {
    $value = "";

    $value .= "<ul>";
    foreach ($this->operads as $operad) {
      $value .= "<li><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["name"] . "</a>";
    }
    $value .= "</ul>";

    return $value;
  }
  public function getSidebar() {
    $value = "";

    return $value;
  }
  public function getTitle() {
    return " &mdash; Operads";
  }
}

?>


