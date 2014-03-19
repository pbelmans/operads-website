<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// read configuration files
require_once("php/config.php");
$config = array_merge($config, parse_ini_file("config.ini"));

// all the pages
require_once("php/pages/about.php");
require_once("php/pages/dimensions.php");
require_once("php/pages/error.php");
require_once("php/pages/index.php");
require_once("php/pages/operad.php");
require_once("php/pages/operads.php");
require_once("php/pages/property.php");
require_once("php/pages/properties.php");
require_once("php/pages/references.php");
require_once("php/pages/search.php");

// we try to construct the page object
try {
  // initialize the global database object
  try {
    $database = new PDO("sqlite:" . $config["database"]);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e) {
    print "Something went wrong with the database.";
    // if there is actually a persistent error: add output code here to check it
    exit();
  }

  if (empty($_GET["page"]))
    $page = "index";
  else
    $page = $_GET["page"];
  
  // all the possible page building scenarios
  switch($page) {
    case "about":
      $page = new AboutPage($database);
      break;

    case "dimensions":
      $page = new DimensionsPage($database);
      break;

    case "index":
      $page = new IndexPage($database);
      break;

    case "operad":
      $page = new OperadPage($database, $_GET["key"]);
      break;

    case "operads":
      $page = new OperadsPage($database);
      break;

    case "property":
      $page = new PropertyPage($database, $_GET["name"]);
      break;

    case "properties":
      $page = new PropertiesPage($database);
      break;

    case "references":
      $page = new ReferencesPage($database);
      break;

    case "search":
      $page = new SearchPage($database);
      break;
  }

  // we request these now so that exceptions are thrown
  $title = $page->getTitle();
  $head = $page->getHead();
  $main = $page->getMain();
}
catch(PDOException $e) {
  $page = new ErrorPage($e);

  // we request these now so that exceptions are thrown
  $title = $page->getTitle();
  $head = $page->getHead();
  $main = $page->getMain();
}

?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Encyclopedia of operads<?php print $title; ?></title>
    <link type="text/css" rel="stylesheet" href="<?php print href("css/main.css"); ?>">
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        tex2jax: {
          inlineMath: [ ['$','$'], ["\\(","\\)"] ],
          displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
          processEscapes: true,
        },
        "HTML-CSS": { availableFonts: ["TeX"], webFont: "Gyre-Pagella" },
        displayAlign: "left"
      });
    </script>
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js"></script>

    <?php print $head; ?>
  </head>

  <body>
    <h1><a href="<?php print href(""); ?>">Encycloperad&mdash;an encyclopedia of operads</a></h1>
    <!-- TODO maybe Operadlas / Operatlas is also a nice name? -->

    <div id="content">
      <ul id="menu">
        <li><a href="<?php print href(""); ?>">home</a>
        <li><a href="<?php print href("operads"); ?>">operads</a>
        <li><a href="<?php print href("properties"); ?>">properties</a>
        <li><a href="<?php print href("dimensions"); ?>">dimensions</a>
        <li><a href="<?php print href("search"); ?>">search</a>
        <li><a href="<?php print href("references"); ?>">references</a>
        <li><a href="<?php print href("about"); ?>">about</a>
      </ul>

      <?php print $main; ?>
    </div>
  </body>
</html>


