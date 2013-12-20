<?php

require_once("php/page.php");
require_once("php/general.php");

class PropertiesPage extends Page {
  private $properties;

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT name FROM properties");

    if ($sql->execute())
      $this->properties = $sql->fetchAll();
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Properties of operads</h2>";
    $value .= "<p>Operads can satisfy the following properties. For each of the properties a description and the list of operads satisfying the property is given.";

    $value .= "<ul>";
    foreach ($this->properties as $property)
      $value .= "<li><a href='" . href("properties/" . $property["name"]) . "'>" . $property["name"] . "</a>";
    $value .= "</ul>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; Properties";
  }
}

?>



