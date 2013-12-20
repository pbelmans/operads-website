<?php

require_once("php/page.php");
require_once("php/general.php");

class SearchPage extends Page {
  public function __construct($database) {
    $this->db = $database;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Search</h2>";
    $value .= "<p>Some ideas:";
    $value .= "<ol>";
    $value .= "<li>select combinations of properties (this search option will also be present on the properties page then)";
    $value .= "<li>search in comments, or by extension every field that contains (or can contain) plaintext";
    $value .= "<li>search a la OEIS";
    $value .= "<li>... ?";
    $value .= "</ol>";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>



