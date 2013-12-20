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

    $value .= "<h2>Advanced property selection</h2>";
    $value .= "<p>this will also appear on the search page";

    $value .= "<h2>Comparison table</h2>";
    $value .= "<p>if the number of properties is not too big, we could make a table: horizontally the properties, vertically the operads";
    $value .= "<p>clicking a column would sort it on 'have the property'</p>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; Properties";
  }
}

?>



