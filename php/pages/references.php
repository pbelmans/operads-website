<?php

require_once("php/page.php");
require_once("php/general.php");
require_once("php/bibtex2html/bibtex2html.php");

function outputBibEntry($entry) {
  $type = trim(strtolower(substr($entry, 1, strpos($entry, '{') - 1)));
  $entry = substr(trim(str_replace(array("\n", "\r", "\t"), array(' ', ' ', ' '), $entry)), 0, -2);
  return bibtex2html($entry, $type, make_accent_table());
}

class ReferencesPage extends Page {
  private $references;

  public function __construct($database) {
    $this->db = $database;

    $sql = $this->db->prepare("SELECT citation_key, operads.key, operads.notation FROM operads, operad_reference WHERE operads.key = operad_reference.key");

    if ($sql->execute())
      $references = $sql->fetchAll();

    foreach ($references as $relation)
      $this->references[$relation["citation_key"]][] = array("key" => $relation["key"], "notation" => $relation["notation"]);
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>References</h2>";
    $value .= "<em>this is work in progress</em>";
    $value .= "<p>the goal is to have a list of all references, as used on the operad pages, and make it browseable in a good way";

    $value .= "<p>possible ways of handling references";
    $value .= "<ul>";
    $value .= "<li>every reference gets assigned a number the first time it is entered in the database";
    $value .= "<li>this number is the key that is used on the website (i.e. the list of references for an operad would contain references to 3, 4 and 23";
    $value .= "<li>this way it is possible (but not advisable) to change keys";
    $value .= "<li>users can refer consistently to the same reference";
    $value .= "<li>this page should list the items ordered by id *and* by author (!) => clicking the 'sort by author' button should add headers for each letter in use, and reorder";
    $value .= "</ul>";
    
    $value .= "<ol>";
    foreach ($this->references as $reference => $operads) {
      $value .= "<li>" . outputBibEntry(extractBibEntry("bib/bibliography.bib", $reference));
      $value .= "<ul class='referencing'>";
      foreach ($operads as $operad)
        $value .= "<li><a href='" . href("operads/" . $operad["key"]) . "'>$" . $operad["notation"] . "$</a>";
      $value .= "</ul>";
    }
    $value .= "</ol>";

    return $value;
  }

  public function getTitle() {
    return " &mdash; References";
  }
}

?>




