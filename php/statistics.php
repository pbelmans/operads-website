<?php

function getNumberOfOperads() {
  global $database;

  $sql = $database->prepare("SELECT COUNT(key) FROM operads");

  if ($sql->execute())
    return $sql->fetchColumn(0);
}

function getNumberOfProperties() {
  global $database;

  $sql = $database->prepare("SELECT COUNT(name) FROM properties");

  if ($sql->execute())
    return $sql->fetchColumn(0);
}

function getNumberOfReferences() {
  global $database;

  $sql = $database->prepare("SELECT COUNT(citation_key) FROM operad_reference");

  if ($sql->execute())
    return $sql->fetchColumn(0);
}

?>
