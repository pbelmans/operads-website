<?php

require_once("php/page.php");
require_once("php/general.php");
require_once("php/bib2html/bib2html.php");

class ReferencesPage extends Page {
  private $properties;

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT citation_key FROM operad_reference");

    if ($sql->execute())
      $this->references = $sql->fetchAll();
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>References</h2>";

    $value .= "<p>possible ways of handling references";
    $value .= "<ul>";
    $value .= "<li>every reference gets assigned a number the first time it is entered in the database";
    $value .= "<li>this number is the key that is used on the website (i.e. the list of references for an operad would contain references to 3, 4 and 23";
    $value .= "<li>this way it is possible (but not advisable) to change keys";
    $value .= "<li>users can refer consistently to the same reference";
    $value .= "<li>this page should list the items ordered by id *and* by author (!) => clicking the 'sort by author' button should add headers for each letter in use, and reorder";
    $value .= "</ul>";
    
    $value .= "<ol>";
    foreach ($this->references as $reference)
      $value .= bibstring2html(extractBibEntry("bib/bibliography.bib", $reference["citation_key"]), null, false, false); 
    $value .= "</ol>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; References";
  }
}

?>




