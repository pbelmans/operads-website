<?php

require_once("php/page.php");
require_once("php/general.php");

function getOperad($key) {
  global $database;

  $sql = $database->prepare("SELECT key, name, notation, dual, series FROM operads WHERE key = :key");
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

    $value .= "<dl class='operad'>";

    $value .= "<dt>Name";
    $value .= "<dd class='name'>" . $this->operad["name"];

    $value .= "<dt>Notation";
    $value .= "<dd class='notation'>$" . $this->operad["notation"] . "$";

    // TODO operations
    // TODO symmetries
    // TODO relations
    //
    // TODO free algebra
    //
    // TODO Sym_n-representation
    // TODO dimension of the representation (if available, the first 7 dimensions are listed)

    $value .= "<dt>Generating series";
    if ($this->operad["series"] != "")
      $value .= "<dd>" . $this->operad["series"];
    else
      $value .= "<dd>not known";
    
    // TODO is the Koszul dual always known?
    // if not: add a check whether the field is non-empty
    $value .= "<dt>Koszul dual";
    $dual = getOperad($this->operad["dual"]);
    $value .= "<dd class='koszul-dual'><a href='" . href("operads/" . $dual["key"]) . "'>$" . $dual["notation"] . "$</a>";

    //
    // TODO chain complex

    $value .= "<dt>Properties";
    $value .= "<dd class='properties'>";
    $value .= "<ul>";
    // TODO something if there are no known properties
    foreach ($this->properties as $property) {
      $value .= "<li><a href='" . href("property/" . $property["name"]) . "'>" . $property["name"] . "</a>";
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

  public function getTitle() {
    return " &mdash; Operad: " . $this->operad["name"];
  }
}

?>

