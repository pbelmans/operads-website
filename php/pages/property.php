<?php

require_once("php/page.php");
require_once("php/general.php");

class PropertyPage extends Page {
  private $name;
  private $operads = array();

  public function __construct($database, $name) {
    $this->db = $database;

    $this->name = $name;

    $sql = $this->db->prepare("SELECT key FROM operad_property WHERE name = :name");
    $sql->bindParam(":name", $name);

    if ($sql->execute())
      $operads = $sql->fetchAll();

      foreach ($operads as $operad)
        $this->operads[] = getOperad($operad["key"]);
  }

  public function getHead() {
    $value = "";

    $value .= OperadPage::getHead();
    $value .= "<script type='text/javascript' src='" . href("js/collapse.js") . "'></script>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Property <em>" . $this->name . "</em></h2>";
    $value .= "<h3>Description</h3>";
    $value .= "<p>Here comes a description";

    $value .= "<h3>Operads (" . count($this->operads) . ") satisfying <em>" . $this->name . "</em></h3>";
    foreach ($this->operads as $operad) // TODO $operad should already contain the properties
      $value .= outputOperad($operad, getPropertiesOfOperad($operad["key"]));

    return $value;
  }

  public function getTitle() {
    return " &mdash; Property"; // TODO some descriptive title
  }
}

?>




