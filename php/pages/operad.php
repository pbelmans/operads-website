<?php

require_once("php/page.php");
require_once("php/general.php");

function getOperad($key) {
  global $database;

  $sql = $database->prepare("SELECT key, name, notation, dual, representation, dimensions, dimension, series FROM operads WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    return $sql->fetch();
}

function getPropertiesOfOperad($key) {
  global $database;

  $sql = $database->prepare("SELECT key, name FROM operad_property WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    return $sql->fetchAll();
}

function outputOperad($operad, $properties) {
  $value = "";

  $value .= "<dl class='operad'>";

  $value .= "<dt>Name";
  $value .= "<dd class='name'>" . $operad["name"];

  $value .= "<dt>Notation";
  $value .= "<dd class='notation'>$" . $operad["notation"] . "$";

  // TODO operations
  // TODO symmetries
  // TODO relations
  //
  // TODO free algebra
  //
  // TODO Sym_n-representation
  $value .= "<dt>$\mathrm{Sym}_n$-representation</dt>";
  if ($operad["representation"] != "")
    $value .= "<dd>$" . $operad["notation"] . "(n)=" . $operad["representation"] . "$";
  else
    $value .= "<dd>not known";
  // TODO dimension of the representation (if available, the first 7 dimensions are listed)

  $value .= "<dt>$\dim" . $operad["notation"] . "(n)$";
  $value .= "<dd class='dimensions'><ol>";
  foreach (explode(",", substr($operad["dimensions"], 1, -1)) as $dimension)
    $value .= "<li>" . trim($dimension);
  $value .= "</ol>";
  if ($operad["dimensions"] != "")
    $value .= "General term: $\dim" . $operad["notation"] . "(n)=" . $operad["dimension"] . "$";
  else
    $value .= "General term: <span class='unknown'>not known</span>";

  $value .= "<dt>Generating series";
  if ($operad["series"] != "")
    $value .= "<dd>" . $operad["series"];
  else
    $value .= "<dd>not known";
  
  // TODO is the Koszul dual always known?
  // if not: add a check whether the field is non-empty
  $value .= "<dt>Koszul dual";
  $dual = getOperad($operad["dual"]);
  $value .= "<dd class='koszul-dual'><a href='" . href("operads/" . $dual["key"]) . "'>$" . $operad["notation"] . "^!=" . $dual["notation"] . "$</a>";

  //
  // TODO chain complex

  $value .= "<dt>Properties";
  $value .= "<dd class='properties'>";
  $value .= "<ul>";
  // TODO something if there are no known properties
  foreach ($properties as $property) {
    $value .= "<li><a href='" . href("properties/" . $property["name"]) . "'>" . $property["name"] . "</a>";
  }
  
  $value .= "</ul>";

  // TODO alternative
  //
  // TODO relationship
  // 
  // TODO unit
  //
  // TODO comment
  //
  // TODO references

  $value .= "</dl>";

  return $value;
}


class OperadPage extends Page {
  private $operad;
  private $properties;

  public function __construct($database, $key) {
    $this->db = $database;

    $this->operad = getOperad($key);
    $this->properties = getPropertiesOfOperad($key);
  }

  public function getHead() {
    $value = "";

    $value .= "<link type='text/css' rel='stylesheet' href='" . href("css/operad.css") . "'>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Operad $" . $this->operad["notation"] . "$</h2>";

    $value .= outputOperad($this->operad, $this->properties); // TODO maybe it's better to construct an operad object with properties in there as a field

    return $value;
  }

  public function getTitle() {
    return " &mdash; Operad: " . $this->operad["name"];
  }
}

?>

