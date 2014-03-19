<?php

require_once("php/page.php");
require_once("php/general.php");

function compareDimensions($a, $b) {
  // the unknown dimensions should be at the end
  if ($a == 0)
    return 1;
  if ($b == 0)
    return -1;

  if ($a == $b)
    return 0;

  // some preprocessing
  $a = explode(",", str_replace(" ", "", $a));
  $b = explode(",", str_replace(" ", "", $b));

  // we sort on the behaviour for small n, not the asymptotic behaviour
  for ($i = 0; $i < min(sizeof($a), sizeof($b)); $i++) {
    if ($a[$i] < $b[$i])
      return -1;
    elseif ($a[$i] > $b[$i])
      return 1;
  }

  // "shorter" lists come first
  if (sizeof($a) < sizeof($b))
    return -1;

  return 0;
}

class OperadsPage extends Page {
  private $dimensions;
  private $operads = array();

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name FROM operads");
    if ($sql->execute())
      $this->operads = $sql->fetchAll();

    $sql = $this->db->prepare("SELECT dimensions, key, name, notation, dimension, dimension_expression FROM operads");
    if ($sql->execute())
      $operads = $sql->fetchAll();

    foreach ($operads as $operad)
      $this->dimensions[substr($operad["dimensions"], 1, -1)][] = $operad;

    uksort($this->dimensions, 'compareDimensions');
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
    $value .= "<p>This is the list of all operads in the database.";

    // TODO there should be some ordering?
    $value .= "<ul>";
    foreach ($this->operads as $operad) {
      $value .= "<li><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["name"] . "</a>";
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


