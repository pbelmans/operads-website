<?php

require_once("php/page.php");
require_once("php/general.php");

class PropertyPage extends Page {
  private $operads = array();

  public function __construct($database, $name) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key FROM operad_property WHERE name = :name");
    $sql->bindParam(":name", $name);

    if ($sql->execute())
      $operads = $sql->fetchAll();

      foreach ($operads as $operad)
        $this->operads[] = getOperad($operad["key"]);
  }
  
  public function getMain() {
    $value = "";

    $value .= "<ul>";
    foreach ($this->operads as $operad)
      $value .= "<li><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["key"] . "</a>";
    $value .= "</ul>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; Property"; // TODO some descriptive title
  }
}

?>




