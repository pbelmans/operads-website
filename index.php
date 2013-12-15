<?php

// read configuration files
require_once("php/config.php");
$config = array_merge($config, parse_ini_file("config.ini"));

// all the pages
require_once("php/pages/error.php");
require_once("php/pages/operad.php");
require_once("php/pages/operads.php");

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
    case "operad":
      $page = new OperadPage($database, $_GET["key"]);
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
    <link type="text/style" rel="stylesheet" href="css/main.css">
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        tex2jax: {
          inlineMath: [ ['$','$'], ["\\(","\\)"] ],
          displayMath: [ ['$$','$$'], ["\\[","\\]"] ],
          processEscapes: true
        },
        "HTML-CSS": { availableFonts: ["TeX"] }
      });
    </script>
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js"></script>

    <?php print $head; ?>
  </head>

  <body>
    <h1>Encyclopedia of operads</h1>

    <?php print $main; ?>
  </body>
</html>


