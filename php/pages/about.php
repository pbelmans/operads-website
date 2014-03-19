<?php

require_once("php/page.php");
require_once("php/general.php");
require_once("php/statistics.php");

class AboutPage extends Page {
  public function __construct($database) {
    $this->db = $database;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>About</h2>";
    $value .= "<p>Some nice description...";
    $value .= " <em>references to Bruno Vallette, Pieter Belmans, Jean-Louis Loday, ...</em>";

    $value .= "<h2>Statistics</h2>";
    $value .= "<p>There are currently";
    $value .= "<ul>"; // TODO link these to the corresponding pages
    $value .= "<li><a href='" . href("operads") . "'>" . getNumberOfOperads() . " operads</a>";
    $value .= "<li><a href='" . href("properties") . "'>" . getNumberOfProperties() . " properties</a>";
    $value .= "<li><a href='" . href("references") . "'>" . getNumberOfReferences() . " references</a>";
    $value .= "</ul>";
    $value .= "in the Encycloperad.";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>



