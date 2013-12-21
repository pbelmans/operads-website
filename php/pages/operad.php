<?php

require_once("php/page.php");
require_once("php/general.php");
require_once("php/bib2html/bib2html.php");

function getOperad($key) {
  global $database;

  // get the operad
  $sql = $database->prepare("SELECT key, name, notation, dual, representation, dimensions, dimension, dimension_expression, series, unit FROM operads WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    $operad = $sql->fetch();

  // get the references for this operad
  $sql = $database->prepare("SELECT key, citation_key FROM operad_reference WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    $references = $sql->fetchAll();
  $operad["references"] = $references;

  return $operad;
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
  $value .= "<dd class='name'><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["name"] . "</a>";

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
  $value .= "<dd class='representation'>";
  if ($operad["representation"] != "")
    $value .= "$" . $operad["notation"] . "(n)=" . $operad["representation"] . "$";
  else
    $value .= outputUnknown();
  // TODO dimension of the representation (if available, the first 7 dimensions are listed)

  $value .= "<dt>$\dim" . $operad["notation"] . "(n)$";
  if ($operad["dimension_expression"] != "")
    $value .= "<dd class='dimensions extendable'>";
  else
    $value .= "<dd class='dimensions'>";
  $value .= "<ol>";
  foreach (explode(",", substr($operad["dimensions"], 1, -1)) as $dimension)
    $value .= "<li>" . trim($dimension);
  $value .= "</ol>";
  if ($operad["dimensions"] != "")
    $value .= "General term: <span class='expression' title='(n-1)!'>$\dim" . $operad["notation"] . "(n)=" . $operad["dimension"] . "$</span>";
  else
    $value .= "General term: " . outputUnknown();

  $value .= "<dt>Generating series";
  $value .= "<dd class='series'>";
  if ($operad["series"] != "")
    $value .= "" . $operad["series"];
  else
    $value .= outputUnknown();
  
  // TODO is the Koszul dual always known?
  // if not: add a check whether the field is non-empty
  $value .= "<dt>Koszul dual";
  $dual = getOperad($operad["dual"]);
  $value .= "<dd class='koszul-dual'><a href='" . href("operads/" . $dual["key"]) . "'>\${" . $operad["notation"] . "}^!=" . $dual["notation"] . "$</a>";

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
  
  $value .= "<dt>Unit";
  $value .= "<dd class='unit'>";
  if ($operad["unit"] != "")
    $value .= $operad["unit"];
  else
    $value .= outputUnknown();
  
  //
  // TODO comment

  $value .= "<dt>References";
  $value .= "<dd class='references'>";
  if (!empty($operad["references"])) {
    $value .= "<ol>";
    foreach ($operad["references"] as $reference)
      $value .= bibstring2html(extractBibEntry("bib/bibliography.bib", $reference["citation_key"]), null, false, false); 
    $value .= "</ol>";
  }
  else
    $value .= "None"; // TODO maybe just don't output anything

  $value .= "</dl>";

  return $value;
}

function outputUnknown() {
  return "<span class='unknown'>not known</span>";
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
    $value .= "<script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.min.js'></script>";
    $value .= "<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/mathjs/0.17.0/math.min.js'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/dimension.js") . "'></script>";

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

