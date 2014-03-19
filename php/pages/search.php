<?php

require_once("php/page.php");
require_once("php/general.php");

class SearchPage extends Page {
  public function __construct($database) {
    $this->db = $database;
  }

  public static function getHead() {
    $value = "";

    $value .= "<link type='text/css' rel='stylesheet' href='" . href("css/search.css") . "'>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>Search</h2>";
    $value .= "<p><em>this is a work in progress, <strong>nothing</strong> is implemented yet</em>";
    $value .= "<p>Some ideas:";
    $value .= "<ol>";
    $value .= "<li>select combinations of properties (this search option will also be present on the properties page then)";
    $value .= "<li>search in comments, or by extension every field that contains (or can contain) plaintext";
    $value .= "<li>search a la OEIS";
    $value .= "<li>... ?";
    $value .= "</ol>";

    $value .= "<hr>";

    $value .= "
    <form>
      <fieldset id='plaintext'>
        <legend>Search plaintext</legend>
        Search through some or all of the fields that can contain plaintext.
        <br>
        <label class='block' for='plaintext-input'>Text</label><input type='search' id='plaintext-input' name='plaintext-input'>
        <br>
        <span class='block'>Fields</span>
        <label class='inline'><input type='checkbox'>Comment</label>
        <label class='inline'><input type='checkbox'>Alternative</label>
        <label class='inline'><input type='checkbox'>Free algebra</label>
        <label class='inline'><input type='checkbox'>Comment</label>
        <br>
        <label class='inline'><input type='checkbox'>Operations</label>
        <label class='inline'><input type='checkbox'>Symmetry</label>
        <label class='inline'><input type='checkbox'>Relations</label>
        <label class='inline'><input type='checkbox'>Comment</label>
        <br style='clear: left;'>
        <button type='submit'>Search</button>
      </fieldset>

      <fieldset id='dimensions'>
        <legend>Search by dimensions</legend>
        Enter a list of integers, and check whether it occurs as the list of dimensions of an operad.
        <p>If you search locally only a naive algorithm is provided. You can also use the full power of the Online Encyclopedia of Integer Sequences, but then you will leave the site.
        <br>
        <label class='block' for='dimensions-input'>Dimensions</label><input type='search' name='dimensions-input'>
        <br>
        <button type='submit'>Search</button><br><button type='submit'>Search on OEIS.org</button>
      </fieldset>
    </form>";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>



