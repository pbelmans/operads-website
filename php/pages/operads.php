<?php

require_once("php/page.php");
require_once("php/general.php");

class OperadsPage extends Page {
  private $operads = array();

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name, notation FROM operads");
    if ($sql->execute())
      $this->operads = $sql->fetchAll();
  }

  public static function getHead() {
    $value = "";

    $value .= "<link type='text/css' rel='stylesheet' href='" . href("css/operads.css") . "'>";
    $value .= "<script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.min.js'></script>"; // TODO put this somewhere more general
    // TODO get these main JS libraries somewhere better
    $value .= "<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/mathjs/0.17.0/math.min.js'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/dimension.js") . "'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/operads.js") . "'></script>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Operads</h2>";
    $value .= "<p>This is the list of all operads in the database, together with their categories of algebras.";

    // TODO there should be some ordering?
    $value .= "<ul>";
    foreach ($this->operads as $operad) {
      $value .= "<li><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["name"] . ":</a> <a href='" . href("operads/" . $operad["key"]) . "'>$" . $operad["notation"] . "$</a>";
    }
    $value .= "</ul>";

    $value .= "<h3>Alternative ways of navigating</h3>";
    $value .= "<p>You might want to browse the operads based on:";
    $value .= "<ul>";
    $value .= "<li><a href='" . href("properties") . "'>their properties</a>";
    $value .= "<li><a href='" . href("properties") . "'>the dimensions of their representations</a>"; // TODO this terminology is probably bad
    $value .= "</ul>";

    return $value;
  }
  public function getSidebar() {
    $value = "";

    return $value;
  }
  public function getTitle() {
    return " &mdash; Operads";
  }
}

?>


