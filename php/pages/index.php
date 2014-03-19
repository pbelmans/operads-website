<?php

require_once("php/page.php");
require_once("php/general.php");

class IndexPage extends Page {
  public function __construct($database) {
    $this->db = $database;
  }

  public static function getHead() {
    $value = "";

    $value .= "<style type='text/css'>p { line-height: 120%; }</style>";

    return $value;
  }
  
  public function getMain() {
    $value = "";

    $value .= "<h2>About</h2>";
    $value .= "<p>This is an encyclopedia dedicated to operads, or types of algebras, and their properties. It is based on the <a href='http://arxiv.org/abs/1101.0267'><em>Encyclopedia of types of algebras</em></a> by Guillaume William Zinbiel (a pseudonym of Jean&ndash;Louis Loday), who started this cornucopia of operads in 2010. <em>to be expanded</em>";

    $value .= "<h2>How to use the website?</h2>";
    $value .= "<p>First of all there is the <a href='" . href("operads") . "'>list of operads</a>, giving a complete overview of all the available operads. Besides this list it is possible to consider <a href='" . href("properties") . "'>operads satisfying certain properties</a>. One can also look at the <a href='" . href("dimensions") . "'>generating series of operads</a>. <em>to be improved</em>";

    return $value;
  }

  public function getTitle() {
    return "";
  }
}

?>


