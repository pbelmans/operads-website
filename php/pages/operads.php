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

    uksort($this->dimensions, compareDimensions);
  }

  public function getHead() {
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

    $value .= "<h2>Dimensions</h2>";
    $numberOfDimensions = 7;

    $value .= "<table id='dimensions'>";
    $value .= "<thead><tr>";
    for ($i = 1; $i <= $numberOfDimensions; $i++)
      $value .= "<th class='dimension'>" . $i . "</th>";
    $value .= "<th class='expression'>general</th>";
    $value .= "<th class='operads'>operads</th>";
    $value .= "</tr></thead>";

    $value .= "<tbody>";
    foreach ($this->dimensions as $dimension => $operads) {
      $value .= "<tr>";
      if ($dimension == 0) {
        for ($i = 1; $i <= $numberOfDimensions; $i++)
          $value .= "<td class='unknown dimension'>?</td>";
        $value .= "<td class='unknown expression' data-expression=''>?</td>";
        $value .= "<td class='operads'>";
        foreach ($operads as $operad) {
          // TODO this should be an UL with the appropriate styling
          $value .= "<a href='" . href("operads/" . $operad["key"]) . "'>$" . $operad["notation"] . "$</a>";
          if ($operad != end($operads))
            $value .= ", ";
        }
        $value .= "</td>";
      }
      else {
        $dimensions = explode(",", str_replace(" ", "", $dimension));
        for ($i = 0; $i < $numberOfDimensions; $i++) {
          if ($i < sizeof($dimensions))
            $value .= "<td class='dimension'>" . $dimensions[$i] . "</td>";
          else
            $value .= "<td class='unknown dimension'>?</td>";
        }

        // TODO there should be a check whether the general expression is always the same (requires consistent notation!)
        $value .= "<td class='expression' data-expression='" . $operads[0]["dimension_expression"] . "'>$" . $operads[0]["dimension"] . "$</td>";

        $value .= "<td class='operads'>"; // TODO this should be a separate function or something, outputListOfOperads as an UL, then we can apply styling whenever we want
        foreach ($operads as $operad) {
          // TODO this should be an UL with the appropriate styling
          $value .= "<a href='" . href("operads/" . $operad["key"]) . "'>$" . $operad["notation"] . "$</a>";
          if ($operad != end($operads))
            $value .= ", ";
        }
        $value .= "</td>";
      }
        

      $value .= "</tr>";
    }
    $value .= "</tbody>";
    $value .= "</table>";

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


