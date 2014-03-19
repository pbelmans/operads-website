<?php

require_once("php/page.php");
require_once("php/general.php");

class PropertiesPage extends Page {
  private $operads;
  private $properties;

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name, slogan, definition FROM properties");
    if ($sql->execute())
      $this->properties = $sql->fetchAll();

    $sql = $this->db->prepare("SELECT operads.key, operads.notation, operad_property.property FROM operads, operad_property WHERE operads.key = operad_property.operad");
    if ($sql->execute())
      $pairs = $sql->fetchAll();
    foreach ($pairs as $pair) {
      $this->operads[$pair["key"]]["notation"] = $pair["notation"];
      $this->operads[$pair["key"]]["properties"][] = $pair["property"];
    }
  }

  public function getHead() {
    $value = "";
    
    $value .= "<link type='text/css' rel='stylesheet' href='" . href("css/properties.css") . "'>";
    $value .= "<script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.min.js'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/tablesorter/js/jquery.tablesorter.js") . "'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/compare.js") . "'></script>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Properties of operads</h2>";
    $value .= "<p>Operads can satisfy the following properties. For each of the properties a description and the list of operads satisfying the property is given.";

    $value .= "<ul>";
    foreach ($this->properties as $property)
      $value .= "<li><a href='" . href("properties/" . $property["key"]) . "'>" . $property["name"] . "</a>";
    $value .= "</ul>";

    $value .= "<h2>Advanced property selection</h2>";
    $value .= "<p>this will also appear on the search page";

    $value .= "<h2>Comparison table</h2>";
    $value .= "<p>if the number of properties is not too big, we could make a table: horizontally the properties, vertically the operads";
    $value .= "<p>clicking a column would sort it on 'have the property'</p>";

    $value .= "<table id='comparison' class='tablesorter'>";
    $value .= "<thead><tr>";
    $value .= "<th>operad</th>";
    foreach ($this->properties as $property)
      $value .= "<th>" . $property["name"] . "</th>";
    $value .= "</tr></thead>";

    $value .= "<tbody>";
    foreach ($this->operads as $key => $operad) {
      $value .= "<tr>";
      $value .= "<th><a href='" . href("operads/" . $key) . "'>$" . $operad["notation"] . "$</a></th>";
      foreach ($this->properties as $property) {
        if (in_array($property["key"], $operad["properties"]))
          $value .= "<td>&#x2713;</td>";
        else
          $value .= "<td></td>";
      }
      $value .= "</tr>";
    }
    $value .= "</tbody>";
    $value .= "</table>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; Properties";
  }
}

?>



