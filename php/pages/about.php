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

    $value .= "<h2>Statistics</h2>";
    $value .= "<p>There are";
    $value .= "<ul>"; // TODO link these to the corresponding pages
    $value .= "<li>" . getNumberOfOperads()[0] . " operads";
    $value .= "<li>" . getNumberOfReferences()[0] . " references";
    $value .= "<li>" . getNumberOfProperties()[0] . " properties";
    $value .= "</ul>";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>



