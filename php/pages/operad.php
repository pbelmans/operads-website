<?php

require_once("php/page.php");
require_once("php/general.php");
require_once("php/bibtex2html/bibtex2html.php");

function getOperad($key) {
  global $database;

  // get the operad
  $sql = $database->prepare("SELECT key, name, notation, dual, representation, dimensions, oeis, dimension, dimension_expression, series, unit FROM operads WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    $operad = $sql->fetch();

  // get the references for this operad
  $sql = $database->prepare("SELECT citation_key FROM operad_reference WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    $references = $sql->fetchAll();
  $operad["references"] = $references;

  // get the expressions for this operad
  $sql = $database->prepare("SELECT category, expression, description FROM expressions WHERE key = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    $expressions = $sql->fetchAll();

  $operad["operations"] = array();
  $operad["symmetries"] = array();
  $operad["relations"] = array();
  foreach ($expressions as $expression) {
    switch ($expression["category"]) {
      case "operation":
        $operad["operations"][] = $expression;
        break;
      case "symmetry":
        $operad["symmetries"][] = $expression;
        break;
      case "relation":
        $operad["relations"][] = $expression;
        break;
    }
  }
  return $operad;
}

function getPropertiesOfOperad($key) {
  global $database;

  $sql = $database->prepare("SELECT properties.key, properties.name, properties.slogan, properties.definition FROM operad_property, properties WHERE properties.key = operad_property.property AND operad_property.operad = :key");
  $sql->bindParam(":key", $key);

  if ($sql->execute())
    return $sql->fetchAll();
}

function outputExpressions($expressions) {
  $value = "";

  if (count($expressions) == 0)
    return ""; // TODO or should we output something like unknown? lack of expression doesn't mean that they are unknown though...
  elseif (count($expressions) == 1)
    $value .= "\\begin{equation}" . $expressions[0]["expression"] . "\\qquad\\text{" . $expressions[0]["description"] . "}\\end{equation}";
  else {
    $value .= "\\begin{equation}\\left\\{\\begin{aligned}{}"; // the {} is to prevent \begin{aligned} from eating [x,y] which is the generating operation for Lie algebras
    foreach ($expressions as $expression) {
      $value .= "\n" . $expression["expression"] . "&\\qquad\\text{" . $expression["description"] . "}";
      if ($expression != end($expressions))
        $value .= "\\\\";
    }
    $value .= "\\end{aligned}\\right.\\end{equation}";
  }

  return $value;
}

function outputOperad($operad, $properties) {
  $value = "";

  $value .= "<dl class='operad'>";

  $value .= "<dt>Name";
  $value .= "<dd class='name'><a href='" . href("operads/" . $operad["key"]) . "'>" . $operad["name"] . "</a>";

  $value .= "<dt>Notation";
  $value .= "<dd class='notation'>$" . $operad["notation"] . "$";

  if (count($operad["operations"]) > 0) {
    $value .= "<dt>Generating operation(s)";
    $value .= "<dd class='operations'>";
    $value .= outputExpressions($operad["operations"]);
  }
  if (count($operad["symmetries"]) > 0) {
    $value .= "<dt>Symmetries";
    $value .= "<dd class='symmetries'>";
    $value .= outputExpressions($operad["symmetries"]);
  }
  if (count($operad["relations"]) > 0) {
    $value .= "<dt>Relations";
    $value .= "<dd class='relations'>";
    $value .= outputExpressions($operad["relations"]);
  }

  // TODO free algebra
  //
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
  if ($operad["dimensions"] != "") {
    $value .= "General term: <span class='expression' data-expression='" . $operad["dimension_expression"] . "'>$\dim" . $operad["notation"] . "(n)=" . $operad["dimension"] . "$</span>";
    if ($operad["oeis"] != "")
      $value .= "<br><a href='http://oeis.org/" . $operad["oeis"] . "'><abbr title='Online Encyclopedia of Integer Sequences'>OEIS</abbr>: <var>" . $operad["oeis"] . "</var></a>";
  }
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
  if (!empty($properties)) {
    $value .= "<ul>";
    // TODO something if there are no known properties
    foreach ($properties as $property)
      $value .= "<li><a href='" . href("properties/" . $property["key"]) . "'>" . $property["name"] . "</a>";
    $value .= "</ul>";
  }
  else
    $value .= "<em>no known properties</em>";

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

  public static function getHead() {
    $value = "";

    $value .= "<link type='text/css' rel='stylesheet' href='" . href("css/operad.css") . "'>";
    $value .= "<script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.min.js'></script>"; // TODO put this somewhere more general
    $value .= "<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/mathjs/0.17.0/math.min.js'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/dimension.js") . "'></script>";
    $value .= "<script type='text/javascript' src='" . href("js/operad.js") . "'></script>";

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

