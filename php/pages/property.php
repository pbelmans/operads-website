<?php

require_once("php/page.php");
require_once("php/general.php");

class PropertyPage extends Page {
  private $property;
  private $operads = array();

  public function __construct($database, $key) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name, slogan, definition FROM properties WHERE key = :key");
    $sql->bindParam(":key", $key);

    if ($sql->execute())
      $this->property = $sql->fetch();

    $sql = $this->db->prepare("SELECT operad FROM operad_property WHERE property = :key");
    $sql->bindParam(":key", $key);

    if ($sql->execute())
      $operads = $sql->fetchAll();

      foreach ($operads as $operad)
        $this->operads[] = getOperad($operad["operad"]);
  }

  public static function getHead() {
    $value = "";

    $value .= OperadPage::getHead();
    $value .= "<script type='text/javascript' src='" . href("js/collapse.js") . "'></script>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Property <em>" . $this->property["name"] . "</em></h2>";
    $value .= "<p><strong>Slogan</strong> ";
    if (!empty($this->property["slogan"]))
      $value .= $this->property["slogan"];
    else
      $value .= "<em>not supplied yet</em>";

    $value .= "<p><strong>Definition</strong> ";
    if (!empty($this->property["definition"]))
      $value .= $this->property["definition"];
    else
      $value .= "<em>not supplied yet</em>";

    $value .= "<h3>Operads (" . count($this->operads) . ") satisfying this property</h3>";
    foreach ($this->operads as $operad) // TODO $operad should already contain the properties
      $value .= outputOperad($operad, getPropertiesOfOperad($operad["key"]));

    return $value;
  }

  public function getTitle() {
    return " &mdash; Property"; // TODO some descriptive title
  }
}

?>




