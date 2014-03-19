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

  public static function getHead() {
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
    $value .= "<p>Operads can satisfy the following properties:";

    $value .= "<ul>";
    foreach ($this->properties as $property) {
      $value .= "<li><a href='" . href("properties/" . $property["key"]) . "'>" . $property["name"] . "</a>";
      if (!empty($property["slogan"]))
        $value .= "&nbsp;&nbsp;(" . $property["slogan"] . ")"; // TODO improve this: DL, with fixed with DT
    }
    $value .= "</ul>";

    $value .= "<p>Clicking on a property will give you a more detailed description of the property, and a list of operads satisfying it.";
    $value .= "<p>If you wish to select operads using combinations of properties, go to the <a href='" . href("search") . "'>search page</a>";

    $value .= "<h2>Comparison table</h2>";

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



