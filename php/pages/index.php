<?php

require_once("php/page.php");
require_once("php/general.php");

class IndexPage extends Page {
  public function __construct($database) {
    $this->db = $database;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>About</h2>";
    $value .= "<p>Some nice description...";

    $value .= "<h2>How to use the website?</h2>";
    $value .= "<p>Some nice description...";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>


