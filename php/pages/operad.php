<?php

require_once("php/page.php");
require_once("php/general.php");

class OperadPage extends Page {
  private $operad;
  private $properties;

  public function __construct($database, $key) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT key, name, notation, dual FROM operads WHERE key = :key");
    $sql->bindParam(":key", $key);

    if ($sql->execute())
      $this->operad = $sql->fetch();

    $sql = $this->db->prepare("SELECT key, name FROM operad_property WHERE key = :key");
    $sql->bindParam(":key", $key);

    if ($sql->execute())
      $this->properties = $sql->fetchAll();
  }
  
  public function getMain() {
    $value = "";

    $value .= "<dl>";

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
    // TODO generating series
    
    // TODO is the Koszul dual always known?
    // if not: add a check whether the field is non-empty
    $value .= "<dt>Koszul dual";
    $value .= "<dd class='koszul-dual'><a href='" . href("operads/" . $this->operad["dual"]) . "'>" . $this->operad["dual"] . "</a>";

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
  public function getSidebar() {
    $value = "";


    return $value;
  }
  public function getTitle() {
    return " &mdash; Operad: " . $this->operad["name"];
  }
}

?>

